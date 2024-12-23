<?php

// Ensure the path to the Composer autoload file is correct
require_once '../vendor/autoload.php';

function base64_to_image($base64_string) {
    // Remove data URI scheme prefix
    $base64_string = str_replace('data:image/png;base64,', '', $base64_string);
    return base64_decode($base64_string);
}

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
        'installer_signature' => $_POST['installer_signature_data'],
        'calfire_signature' => $_POST['calfire_signature_data']
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
                $device_stmt = $conn->prepare("INSERT INTO installation_devices (installation_id, device_id, serial, asset) VALUES (?, ?, ?, ?)");
                foreach ($devices as $device) {
                    $device_id = getDeviceIdBySku($conn, $device['Sku']);
                    if ($device_id !== null) { // Check if device_id is not null
                        $device_stmt->bind_param("iiss", $installation_id, $device_id, $device['serial'], $device['asset']);
                        $device_stmt->execute();
                    }
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
    // Redirect to the PDF generation script with form data
    header('Location: generate_pdf.php?' . http_build_query($formData));
    exit();
}
?>
