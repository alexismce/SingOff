<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $formData = $_GET;

    function generatePDF($formData) {
        // Ensure the path to the TCPDF library is correct
        require_once '../vendor/tcpdf/tcpdf.php';

        // Define constants if they are not defined
        if (!defined('PDF_PAGE_ORIENTATION')) define('PDF_PAGE_ORIENTATION', 'P');
        if (!defined('PDF_UNIT')) define('PDF_UNIT', 'mm');
        if (!defined('PDF_PAGE_FORMAT')) define('PDF_PAGE_FORMAT', 'A4');
        if (!defined('PDF_CREATOR')) define('PDF_CREATOR', 'TCPDF');
        if (!defined('PDF_FONT_NAME_MAIN')) define('PDF_FONT_NAME_MAIN', 'helvetica');
        if (!defined('PDF_FONT_SIZE_MAIN')) define('PDF_FONT_SIZE_MAIN', 10);
        if (!defined('PDF_FONT_NAME_DATA')) define('PDF_FONT_NAME_DATA', 'helvetica');
        if (!defined('PDF_FONT_SIZE_DATA')) define('PDF_FONT_SIZE_DATA', 8);
        if (!defined('PDF_MARGIN_LEFT')) define('PDF_MARGIN_LEFT', 15);
        if (!defined('PDF_MARGIN_RIGHT')) define('PDF_MARGIN_RIGHT', 15);
        if (!defined('PDF_MARGIN_TOP')) define('PDF_MARGIN_TOP', 27);
        if (!defined('PDF_MARGIN_BOTTOM')) define('PDF_MARGIN_BOTTOM', 25);
        if (!defined('PDF_MARGIN_HEADER')) define('PDF_MARGIN_HEADER', 5);
        if (!defined('PDF_MARGIN_FOOTER')) define('PDF_MARGIN_FOOTER', 10);
        if (!defined('PDF_IMAGE_SCALE_RATIO')) define('PDF_IMAGE_SCALE_RATIO', 1.25);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Alexis Felix');
        $pdf->SetTitle('Installation Form');
        $pdf->SetSubject('Installation Form Details');
        $pdf->SetKeywords('TCPDF, PDF, installation, form');

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT); // Adjust the margins correctly
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
            $img_data = '@' . $formData['installer_signature'];
            $pdf->Image($img_data, 15, 160, 0, 0, 'PNG');
        }
        $html .= '</td>';
        $html .= '<td width="50%">';
        if (!empty($formData['calfire_signature'])) {
            $img_data = '@' . $formData['calfire_signature'];
            $pdf->Image($img_data, 15, 160, 0, 0, 'PNG');
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

    generatePDF($formData);
}

?>
