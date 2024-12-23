<?php
require_once 'tcpdf/tcpdf.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdf = new TCPDF();
    $pdf->AddPage();

    // Collect form data and add it to the PDF
    $date = $_POST['date'];
    $radio_number = $_POST['radio_number'];
    $cal_fire_unit_code = $_POST['cal_fire_unit_code'];
    $x_number = $_POST['x_number'];
    $radio_mobile_mid = $_POST['radio_mobile_mid'];
    $license_plate = $_POST['license_plate'];
    $mileage = $_POST['mileage'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $avl1_check = $_POST['avl1_check'];
    $previously_installed = $_POST['previously_installed'];
    $system_test = $_POST['system_test'];
    $installationType = $_POST['installationType'];
    $installer_name = $_POST['installer_name'];
    $calfire_officer_name = $_POST['calfire_officer_name'];
    $device_data = json_decode($_POST['device_data'], true);

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // Add form data to PDF
    $pdf->Cell(0, 10, "Date: $date", 0, 1);
    $pdf->Cell(0, 10, "Radio Number: $radio_number", 0, 1);
    $pdf->Cell(0, 10, "UNIT Code: $cal_fire_unit_code", 0, 1);
    $pdf->Cell(0, 10, "X-Number: $x_number", 0, 1);
    $pdf->Cell(0, 10, "Radio Mobile MID: $radio_mobile_mid", 0, 1);
    $pdf->Cell(0, 10, "License Plate #: $license_plate", 0, 1);
    $pdf->Cell(0, 10, "Mileage: $mileage", 0, 1);
    $pdf->Cell(0, 10, "Make: $make", 0, 1);
    $pdf->Cell(0, 10, "Model: $model", 0, 1);
    $pdf->Cell(0, 10, "Was this equipment an AVL 1.0?: $avl1_check", 0, 1);
    $pdf->Cell(0, 10, "Previously Installed Equipment: $previously_installed", 0, 1);
    $pdf->Cell(0, 10, "System Test and Approval: $system_test", 0, 1);
    $pdf->Cell(0, 10, "Installation Type: $installationType", 0, 1);
    $pdf->Cell(0, 10, "Installer Name: $installer_name", 0, 1);
    $pdf->Cell(0, 10, "CalFire Officer Name: $calfire_officer_name", 0, 1);

    // Add device data to PDF
    $pdf->Cell(0, 10, "Devices:", 0, 1);
    foreach ($device_data as $device) {
        $pdf->Cell(0, 10, "{$device['Sku']} - {$device['description']} - Serial: {$device['serial']} - Asset: {$device['asset']}", 0, 1);
    }

    // Signature images
    if (isset($_POST['installer_signature'])) {
        $installer_signature = $_POST['installer_signature'];
        $installer_signature = str_replace('data:image/png;base64,', '', $installer_signature);
        $installer_signature = str_replace(' ', '+', $installer_signature);
        $installer_data = base64_decode($installer_signature);
        $pdf->Image('@' . $installer_data, 15, 240, 50, 25, 'PNG');
        $pdf->Cell(0, 10, "Installer Signature:", 0, 1);
    }

    if (isset($_POST['calfire_signature'])) {
        $calfire_signature = $_POST['calfire_signature'];
        $calfire_signature = str_replace('data:image/png;base64,', '', $calfire_signature);
        $calfire_signature = str_replace(' ', '+', $calfire_signature);
        $calfire_data = base64_decode($calfire_signature);
        $pdf->Image('@' . $calfire_data, 15, 270, 50, 25, 'PNG');
        $pdf->Cell(0, 10, "CalFire Officer Signature:", 0, 1);
    }

    // Output the PDF
    $pdf->Output('signoff.pdf', 'I');
}
?>
