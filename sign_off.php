<?php
require_once 'includes/db_connect.php'; // Ensure this file is included to establish a database connection

session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign-Off Process</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="container">
    <h2>Equipment Installation Form</h2>
    <form method="post" action="includes/sign_off_process.php">
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="date">Date</label>
          <input type="date" class="form-control" id="date" name="date" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="radio_number">RADIO #/VEHICLE Name</label>
          <input type="text" class="form-control" id="radio_number" name="radio_number" required>
        </div>
        <div class="form-group col-md-4">
          <label for="cal_fire_unit_code">UNIT Code</label>
          <input type="text" class="form-control" id="cal_fire_unit_code" name="cal_fire_unit_code" required>
        </div>
        <div class="form-group col-md-4">
          <label for="x_number">X-Number</label>
          <input type="text" class="form-control" id="x_number" name="x_number" placeholder="e.g., 12 X 345" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="radio_mobile_mid">Radio Mobile MID</label>
          <input type="text" class="form-control" id="radio_mobile_mid" name="radio_mobile_mid">
        </div>
        <div class="form-group col-md-4">
          <label for="license_plate">License Plate #</label>
          <input type="text" class="form-control" id="license_plate" name="license_plate" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="mileage">Mileage</label>
          <input type="number" class="form-control" id="mileage" name="mileage" required>
        </div>
        <div class="form-group col-md-4">
          <label for="make">Make</label>
          <input type="text" class="form-control" id="make" name="make">
        </div>
        <div class="form-group col-md-4">
          <label for="model">Model</label>
          <input type="text" class="form-control" id="model" name="model">
        </div>
      </div>
      <div class="form-group">
        <label for="avl1_check">Was this equipment an AVL 1.0?</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" id="avl1_yes" name="avl1_check" value="yes">
          <label class="form-check-label" for="avl1_yes">Yes</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" id="avl1_no" name="avl1_check" value="no">
          <label class="form-check-label" for="avl1_no">No</label>
        </div>
      </div>
      <div class="form-group">
        <label for="previously_installed">Previously Installed Equipment (optional description)</label>
        <input type="text" class="form-control" id="previously_installed" name="previously_installed" placeholder="Description">
      </div>
      <div class="form-group">
        <label>System Test and Approval</label>
        <table class="table">
          <thead>
            <tr>
              <th>Description</th>
              <th>Yes/No</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>MDT, Cell, RF and Satellite tested successfully</td>
              <td>
                <select class="form-control" name="system_test" aria-label="System Test" required>
                  <option value="" disabled selected>Select Yes/No</option>
                  <option value="Yes">Yes</option>
                  <option value="No">No</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="form-group">
        <label for="installationType">Installation Type:</label>
        <select class="form-control" id="installationType" name="installationType" required>
          <option value="">Select Installation Type</option>
          <option value="type1">1</option>
          <option value="type2">2</option>
          <option value="type3">3</option>
          <option value="type4">4</option>
          <option value="type5">5</option>
        </select>
      </div>

      <table class="table table-striped hide-sku">
        <thead>
        <tr>
          <th>#</th>
          <th>Item</th>
          <th>Description</th>
          <th>Options</th>
          <th>Serial Number</th>
          <th>Asset Tag</th>
        </tr>
        </thead>
        <tbody id="deviceTableBody"></tbody>
      </table>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="installer_name">Installer Name:</label>
          <input type="text" class="form-control" id="installer_name" name="installer_name" required>
        </div>
        <div class="form-group col-md-6">
          <label for="calfire_officer_name">CalFire Officer Name:</label>
          <input type="text" class="form-control" id="calfire_officer_name" name="calfire_officer_name" required>
        </div>
      </div>
        </thead>
</table>
<div class="form-row">
  <div class="form-group col-md-6">
    <label for="installer-signature-pad">Installer Signature:</label>
    <div class="signature-container">
      <canvas id="installer-signature-pad" class="signature-pad" width="450" height="200"></canvas>
    </div>
  </div>
  <div class="form-group col-md-6">
    <label for="calfire-signature-pad">CalFire Officer Signature:</label>
    <div class="signature-container">
      <canvas id="calfire-signature-pad" class="signature-pad" width="450" height="200"></canvas>
    </div>
  </div>
  
  <input type="hidden" name="device_data" id="device_data">

<div class="button-group">
  <button type="button" id="clear" class="btn btn-secondary">Clear</button>
  <button type="submit" class="btn btn-primary">Submit</button>
  <button type="button" id="generatePdf" class="btn btn-info">Generate PDF</button>
</div>
</form>
</div>
    <script src="\js\signature_pad.umd.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const installerCanvas = document.getElementById('installer-signature-pad');
        const calfireCanvas = document.getElementById('calfire-signature-pad');
        if (installerCanvas && calfireCanvas) {
          const installerSignaturePad = new SignaturePad(installerCanvas);
          const calfireSignaturePad = new SignaturePad(calfireCanvas);
          // Additional initialization code
        } else {
          console.error("Canvas element not found!");
        }
      });
    </script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const installerCanvas = document.getElementById('installer-signature-pad');
    const calfireCanvas = document.getElementById('calfire-signature-pad');
    if (installerCanvas && calfireCanvas) {
      const installerSignaturePad = new SignaturePad(installerCanvas);
      const calfireSignaturePad = new SignaturePad(calfireCanvas);
      // Additional initialization code
    } else {
      console.error("Canvas element not found!");
    }
  });
</script>
<script>
  // Prepopulate Date
  const dateField = document.getElementById('date');
  if (dateField) {
    const today = new Date();
    today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
    const localDate = today.toISOString().split('T')[0];
    dateField.value = localDate;
  }
</script>
<script>
  document.getElementById('clear').addEventListener('click', function() {
    const installerCanvas = document.getElementById('installer-signature-pad');
    const calfireCanvas = document.getElementById('calfire-signature-pad');
    if (installerCanvas && calfireCanvas) {
      const installerSignaturePad = new SignaturePad(installerCanvas);
      const calfireSignaturePad = new SignaturePad(calfireCanvas);
      installerSignaturePad.clear();
      calfireSignaturePad.clear();
    } else {
      console.error("Canvas element not found!");
    }
  });
  document.getElementById('generatePdf').addEventListener('click', function() {
    const form = document.getElementById('equipmentForm');

  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const installerCanvas = document.getElementById('installer-signature-pad');
    const calfireCanvas = document.getElementById('calfire-signature-pad');
    const installerSignaturePad = new SignaturePad(installerCanvas);
    const calfireSignaturePad = new SignaturePad(calfireCanvas);
      
    const installerSignatureDataInput = document.createElement('input');
    installerSignatureDataInput.type = 'hidden';
    installerSignatureDataInput.name = 'installer_signature_data';
    document.getElementById('equipmentForm').appendChild(installerSignatureDataInput);
      
    const calfireSignatureDataInput = document.createElement('input');
    calfireSignatureDataInput.type = 'hidden';
    calfireSignatureDataInput.name = 'calfire_signature_data';
    document.getElementById('equipmentForm').appendChild(calfireSignatureDataInput);
      
    document.getElementById('generatePdf').addEventListener('click', function() {
        installerSignatureDataInput.value = installerSignaturePad.toDataURL();
        calfireSignatureDataInput.value = calfireSignaturePad.toDataURL();
        
        // Trigger the form submission, you need to send this to the php file
        document.getElementById('equipmentForm').submit();
      });
      
    document.getElementById('clear').addEventListener('click', function() {
      installerSignaturePad.clear();
      calfireSignaturePad.clear();
      });
  });
</script>
</body>
</html>
