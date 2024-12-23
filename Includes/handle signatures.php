<?php

function base64_to_image($base64_string) {
    // Remove data URI scheme prefix
    $base64_string = str_replace('data:image/png;base64,', '', $base64_string);
    return base64_decode($base64_string);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $installer_signature = isset($_POST['installer_signature_data']) ? $_POST['installer_signature_data'] : null;
    $calfire_signature = isset($_POST['calfire_signature_data']) ? $_POST['calfire_signature_data'] : null;

    $response = [];

    if ($installer_signature) {
        $response['installer_signature'] = base64_to_image($installer_signature);
    }

    if ($calfire_signature) {
        $response['calfire_signature'] = base64_to_image($calfire_signature);
    }

    echo json_encode($response);
}

?>
