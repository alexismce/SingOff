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

/**
 * @param mixed $conn 
 * @param mixed $sku 
 * @return mixed 
 */
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

/**
 * @param mixed $formData 
 * @return void 
 */
function generatePDF($formData) {
    require_once '../vendor/autoload.php';

    // Add this line to use the TCPDF namespace
    use TCPDF;
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator('Your Application Name');
    $pdf->SetAuthor('Alexis Felix');
    $pdf->SetTitle('Installation Form');
    $pdf->SetSubject('Installation Form Details');
    $pdf->SetKeywords('TCPDF, PDF, installation, form');

    $pdf->setHeaderFont(Array('helvetica', '', 12));
    $pdf->setFooterFont(Array('helvetica', '', 10));

    $pdf->SetDefaultMonospacedFont('courier');
    $pdf->SetMargins(15, 10, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 25);
    $pdf->setImageScale(1.25);

    $pdf->AddPage();

    $pdf->SetFont('helvetica', '', 12);

    // Add your PDF content generation logic here

    // Output the PDF
    $pdf->Output('installation_form.pdf', 'F');
}

function generatePDF($formData) {
    require_once '../vendor/autoload.php';

    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator('Your Application Name');
    $pdf->SetAuthor('Alexis Felix');
    $pdf->SetTitle('Installation Form');
    $pdf->SetSubject('Installation Form Details');
    $pdf->SetKeywords('TCPDF, PDF, installation, form');

    $pdf->setHeaderFont(Array('helvetica', '', 12));
    $pdf->setFooterFont(Array('helvetica', '', 10));

    $pdf->SetDefaultMonospacedFont('courier');
    $pdf->SetMargins(15, 10, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 25);
    $pdf->setImageScale(1.25);

    $pdf->AddPage();

    $pdf->SetFont('helvetica', '', 12);

    // Add your PDF content generation logic here

    // Output the PDF
    $pdf->Output('installation_form.pdf', 'F');
}

?>


