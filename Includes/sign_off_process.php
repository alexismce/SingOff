<?php


// Ensure the path to the Composer autoload file is correct
require_once '../vendor/autoload.php';

// Add this function at the top of the file after require_once
function base64_to_image($base64_string) {
    // Remove data URI scheme prefix
    $base64_string = str_replace('data:image/png;base64,', '', $base64_string);
    return base64_decode($base64_string);
}

// Start output buffering
ob_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'installation_date' => $_POST['date'],
        'radio_number' => $_POST['radio_number'],
        'unit_code' => $_POST['cal_fire_unit_code'],
        'x_number' => $_POST['x_number'],
        'radio_mobile_mid' => $_POST['radio_mobile_mid'],
        'license_plate' => $_POST['license_plate'],
        'mileage' => $_POST['mileage'],
        'make' => $_POST['make'],
        'model' => $_POST['model'],
        'avl1_check' => $_POST['avl1_check'],
        'previously_installed' => $_POST['previously_installed'],
        'system_test' => $_POST['system_test'],
        'installation_type' => $_POST['installationType'],
        'installer_name' => $_POST['installer_name'],
        'calfire_officer_name' => $_POST['calfire_officer_name'],
        'device_data' => isset($_POST['device_data']) ? $_POST['device_data'] : null,
        'installer_signature' => $_POST['installer_signature'],
        'calfire_signature' => $_POST['calfire_signature']
    ];

    // Database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind for installations
    $stmt = $conn->prepare("INSERT INTO installations (installation_date, radio_number, unit_code, x_number, radio_mobile_mid, license_plate, mileage, make, model, avl1_check, previously_installed, system_test, installation_type, installer_name, calfire_officer_name, device_data, installer_signature, calfire_signature) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssisssssssssss", $formData['installation_date'], $formData['radio_number'], $formData['unit_code'], $formData['x_number'], $formData['radio_mobile_mid'], $formData['license_plate'], $formData['mileage'], $formData['make'], $formData['model'], $formData['avl1_check'], $formData['previously_installed'], $formData['system_test'], $formData['installation_type'], $formData['installer_name'], $formData['calfire_officer_name'], $formData['device_data'], $formData['installer_signature'], $formData['calfire_signature']);

    // Execute the query for installations
    if ($stmt->execute()) {
        $installation_id = $stmt->insert_id; // Get the last inserted ID for installations

        // Insert device data if available
        if ($formData['device_data']) {
            $devices = json_decode($formData['device_data'], true);
            if ($devices) {
                $device_stmt = $conn->prepare("INSERT INTO installation_devices (installation_id, device_id, serial, asset, variant) VALUES (?, ?, ?, ?, ?)");
                foreach ($devices as $device) {
                    $device_id = getDeviceIdBySku($conn, $device['sku']);
                    if ($device_id !== null) { //check if device_id is not null
                        $device_stmt->bind_param("iisss", $installation_id, $device_id, $device['serial'], $device['asset'], $device['variant']);
                    }
                    $device_stmt->execute();
                }
                $device_stmt->close();
            }
        }

        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    generatePDF($formData);
}

function getDeviceIdBySku($conn, $sku) {
    $stmt = $conn->prepare("SELECT id FROM devices WHERE sku = ?");
    $stmt->bind_param("s", $sku);
    $stmt->execute();
    $result = $stmt->get_result();
    $device_id = null;
    if ($row = $result->fetch_assoc()) {
        $device_id = $row['id'];
    }
    $stmt->close();    
    return $device_id;
}

function generatePDF($formData) {
    // Ensure the path to the TCPDF library is correct
    require_once '../vendor/tcpdf/tcpdf.php';

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Alexis Felix');
    $pdf->SetTitle('Installation Form');
    $pdf->SetSubject('Installation Form Details');
    $pdf->SetKeywords('TCPDF, PDF, installation, form');

    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT); // Adjust the top margin to 10
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->AddPage();

    $pdf->SetFont('helvetica', '', 12);

    $html = '<h1>Installation Form Details</h1>';
    $html .= '<p><strong>Date:</strong> ' . htmlspecialchars($formData['installation_date']) . '</p>';
    $html .= '<table border="1" cellpadding="3">
                <tr>
                    <td><strong>Radio Number:</strong> ' . htmlspecialchars($formData['radio_number']) . '</td>
                    <td><strong>Unit Code:</strong> ' . htmlspecialchars($formData['unit_code']) . '</td>
                    <td><strong>X Number:</strong> ' . htmlspecialchars($formData['x_number']) . '</td>
                </tr>
                <tr>
                    <td><strong>Radio Mobile MID:</strong> ' . htmlspecialchars($formData['radio_mobile_mid']) . '</td>
                    <td><strong>License Plate:</strong> ' . htmlspecialchars($formData['license_plate']) . '</td>
                    <td><strong>Mileage:</strong> ' . htmlspecialchars($formData['mileage']) . '</td>
                </tr>
                <tr>
                    <td><strong>Make:</strong> ' . htmlspecialchars($formData['make']) . '</td>
                    <td><strong>Model:</strong> ' . htmlspecialchars($formData['model']) . '</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Installation Type:</strong> ' . htmlspecialchars($formData['installation_type']) . '</td>
                </tr>
              </table>';
    $html .= '<p><strong>Was this equipment an AVL 1.0?:</strong> ' . htmlspecialchars($formData['avl1_check']) . '</p>';
    $html .= '<p><strong>Previously Installed Equipment (optional description):</strong> ' . htmlspecialchars($formData['previously_installed']) . '</p>';
    $html .= '<p><strong>System Test and Approval:</strong> ' . htmlspecialchars($formData['system_test']) . '</p>';

    // Assuming device data is an array of arrays
    if ($formData['device_data']) {
        $devices = json_decode($formData['device_data'], true);
        if ($devices) {
            $html .= '<h2>Devices</h2>';
            $html .= '<table border="1" cellpadding="5"><tr><th style="width: 5%;">#</th><th style="width: 40%;">Description</th><th>Serial Number</th><th>Asset Tag</th></tr>';
            foreach ($devices as $index => $device) {
                $html .= '<tr><td style="width: 5%;">' . ($index + 1) . '</td><td style="width: 40%;">' . htmlspecialchars($device['description']) . '</td><td>' . htmlspecialchars($device['serial']) . '</td><td>' . htmlspecialchars($device['asset']) . '</td></tr>';
            }
            $html .= '</table>';
        } else {
            $html .= '<p>No devices found.</p>';
        }
    } else {
        $html .= '<p>Device data not received.</p>';
    }

    // Add signatures
    $html .= '<h2>Signatures</h2>';    
    $html .= '<table border="0" cellpadding="5">';
    $html .= '<tr>';
    $html .= '<td width="50%">';
    if (!empty($formData['installer_signature'])) {
        $img_data = base64_to_image($formData['installer_signature']);
        $pdf->Image('@' . $img_data, 15, 160, 0, 0, 'PNG');
    }
    $html .= '</td>';
    $html .= '<td width="50%">';
    if (!empty($formData['calfire_signature'])) {
        $img_data = base64_to_image($formData['calfire_signature']);
        $pdf->Image('@' . $img_data, 15, 160, 0, 0, 'PNG');
    }
    $html .= '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td width="50%" align="center"><strong>Installer:</strong> ' . htmlspecialchars($formData['installer_name']) . '</td>';
    $html .= '<td width="50%" align="center"><strong>CalFire Officer:</strong> ' . htmlspecialchars($formData['calfire_officer_name']) . '</td>';
    $html .= '</tr>';
    $html .= '</table>';

    // Write HTML content
    $pdf->writeHTML($html, true, false, true, false, '');

    // End output buffering and clean it
    ob_end_clean();
    
    // Output PDF
    $pdf->Output('installation_form.pdf', 'I');
}
?>


