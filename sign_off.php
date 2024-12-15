<?php
header('Cache-Control: public, max-age=31536000, immutable');
header('X-Content-Type-Options: nosniff');
header('Content-Type: text/html; charset=utf-8');

require_once 'includes/db_connect.php';
require_once 'includes/sign_off_process.php';
// Your existing code here
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-Content-Type-Options" content="nosniff">
  <title>Equipment Installation Form</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
  <script src="/js/signature_pad.umd.min.js"></script>
  <style>
    const pdfCss = `
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin: 20px; 
        }
        .form-control, .signature-pad {
            border: 1px solid #000; 
        }
        .signature-pad {
            width: 300px; 
            height: 150px; 
        }
        /* Add more styles as needed */
      `;
  </style>
  <style>
    .disabled {
      opacity: 0.5;
      pointer-events: none;
    }
    body {
      background-color: #f9f9f9;
      font-family: Arial, sans-serif;
    }
    .container {
      margin-top: 20px;
      background-color: #fff;
      padding: 20px;
      border-radius: 6px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
      margin-bottom: 20px;
      color: #333;
    }
    .form-group label {
      font-weight: bold;
    }
    .form-control {
      border-radius: 4px;
    }
    .signature-pad {
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-top: 10px;
    }
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }
    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #004085;
    }
    .btn-secondary {
      background-color: #6c757d;
      border-color: #6c757d;
    }
    .btn-secondary:hover {
      background-color: #5a6268;
      border-color: #545b62;
    }
    .signature-box {
      flex: 1;
      margin-right: 10px;
    }
    .signature-box:last-child {
      margin-right: 0;
    }
    .signature-pad {
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .custom-checkbox {
      width: 20px; /* Control the size */
      height: 20px;
      cursor: pointer;
    }
    .custom-checkbox {
        background-color: #007bff; /* Change to your preferred color */
        border-radius: 3px;
    }
    /* New way using Forced Colors Mode */
    @media (forced-colors: active) { 
        body { background-color: Canvas; color: CanvasText; } 
    }
    /* Hide SKU column */
    .hide-sku th:nth-child(2),
    .hide-sku td:nth-child(2) {
        display: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Equipment Installation Form</h2>
    <form id="equipmentForm" method="POST" action="includes/sign_off_process.php">
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

  const installationTypeSelect = document.getElementById('installationType');
  const deviceTableBody = document.getElementById('deviceTableBody');
  const deviceDataInput = document.getElementById('device_data');
      
  const allDevices = [
    {Sku: 'mdt10', description: 'RMI Patriot 10" MDC', serial: '', asset: ''},
    {Sku: 'mdt12', description: 'RMI Patriot 12" MDC', serial: '', asset: ''},
    {Sku: 'PLA2', description: 'RMI Installation Plate A', serial: ''},
    {Sku: 'PLB2', description: 'RMI Installation Plate B', serial: ''},
    {Sku: 'RF930', description: 'RMI IQ Modem/Tait Radio', serial: '', asset: ''},
    {Sku: 'R931', description: 'Cradlepoint IMEI No', serial: '', asset: ''},
    {Sku: 'PSEC5', description: 'Parsec Antenna (5 in 1)', serial: '', asset: ''},
    {Sku: 'PSEC7', description: 'Parsec Antenna (7 in 1)', serial: '', asset: ''},
    {Sku: 'ORB61', description: 'Orbcomm ST6100 Serial No', serial: '', asset: ''},
    {Sku: 'VFA1', description: 'VHF Radio Antenna', serial: '', asset: ''},
    {Sku: 'PSC', description: 'PSC S Code', serial: '', asset: ''},
    {Sku: 'HB12', description: 'RMI RMHB 12"', serial: '', asset: ''},
    {Sku: 'HB15', description: 'RMI RMHB 15"', serial: '', asset: ''},
    {Sku: 'EMRSW1', description: 'RMI EMER Button Kit', serial: '', asset: ''},
  ];

  const installationTypeMapping = {
    type1: ['mdt10', 'PLA2', 'RF930', 'R931', 'PSEC5', 'PSEC7', 'ORB61', 'VFA1', 'PSC'],
    type2: ['mdt12', 'HB12', 'PLA2', 'RF930', 'R931', 'PSEC5', 'PSEC7', 'ORB61', 'VFA1', 'PSC'],
    type3: ['mdt10', 'R931', 'PSEC5', 'PSEC7', 'ORB61'],
    type4: ['PLA2', 'R931', 'PSEC5', 'PSEC7', 'ORB61'],
    type5: ['EMRSW1', 'ORB61']
  };

  const disabledAssetTags = [
    'RMI Installation Plate A',
    'RMI Installation Plate B',
    'PSC S Code',
    'VHF Radio Antenna',
    'Parsec Antenna (5 in 1)',
    'Parsec Antenna (7 in 1)',
    'RMI EMER Button Kit',
];

  function togglePatriotSize(button) {
    const row = button.closest('tr');
    const itemCell = row.cells[1];
    const descCell = row.cells[2];
    if (button.textContent === '10"') {
      button.textContent = '12"';
      itemCell.textContent = 'mdt12';
      descCell.textContent = 'RMI Patriot 12" MDC';
    } else {
      button.textContent = '10"';
      itemCell.textContent = 'mdt10';
      descCell.textContent = 'RMI Patriot 10" MDC';
    }
  }

  function toggleRMHBSize(button) {
    const row = button.closest('tr');
    const itemCell = row.cells[1];
    const descCell = row.cells[2];
    if (button.textContent === '12"') {
      button.textContent = '15"';
      itemCell.textContent = 'HB15';
      descCell.textContent = 'RMI RMHB 15"';
    } else {
      button.textContent = '12"';
      itemCell.textContent = 'HB12';
      descCell.textContent = 'RMI RMHB 12"';
    }
  }

  function togglePlateType(button) {
    const row = button.closest('tr');
    const itemCell = row.cells[1];
    const descCell = row.cells[2];
    if (button.textContent === 'A') {
      button.textContent = 'B';
      itemCell.textContent = 'PLB2';
      descCell.textContent = 'RMI Installation Plate B';
    } else {
      button.textContent = 'A';
      itemCell.textContent = 'PLA2';
      descCell.textContent = 'RMI Installation Plate A';
    }
  }

  function updateForm() {
    const selectedInstallationType = installationTypeSelect.value;
    const deviceSkus = installationTypeMapping[selectedInstallationType];

    if (!deviceSkus) {
      deviceTableBody.innerHTML = '<tr><td colspan="6">No devices found for this installation type.</td></tr>';
      return;
    }

    deviceTableBody.innerHTML = '';
    const deviceData = [];

    deviceSkus.forEach((sku, index) => {
      const device = allDevices.find(device => device.Sku === sku);

      let toggleButton = '';
      if (sku === 'mdt10' || sku === 'mdt12') {
        toggleButton = `<button type="button" class="btn btn-secondary btn-sm" onclick="togglePatriotSize(this)" style="background-color: #007bff; color: #fff;">10"</button>`;
      } else if (sku === 'HB12' || sku === 'HB15') {
        toggleButton = `<button type="button" class="btn btn-secondary btn-sm" onclick="toggleRMHBSize(this)" style="background-color: #007bff; color: #fff;">12"</button>`;
      } else if (sku === 'PLA2' || sku === 'PLB2') {
        toggleButton = `<button type="button" class="btn btn-secondary btn-sm" onclick="togglePlateType(this)" style="background-color: #007bff; color: #fff;">A</button>`;
      }

    const disabledAttr = disabledAssetTags.includes(device.description) ? 'disabled' : '';

// Ensure no 'undefined' appears in the asset tag field
const assetTagValue = device.asset !== undefined ? device.asset : '';

const row = document.createElement('tr');
row.innerHTML = `
  <td>${index + 1}</td>
  <td>${device.Sku}</td>
  <td>${device.description}</td>
  <td>${toggleButton}</td>
  <td>
    ${device.checkbox !== undefined 
      ? `<input type="checkbox" class="form-check-input custom-checkbox" name="${device.Sku}_installed" id="${device.Sku}_checkbox">` 
      : `<input type="text" class="form-control" name="serial_number[]" value="${device.serial}" required>`}
  </td>
  <td>
    <input type="text" class="form-control" name="asset_tag[]" value="${assetTagValue}" ${disabledAttr}>
  </td>
`;


deviceTableBody.appendChild(row);
deviceData.push({ 
  Sku: device.Sku, 
  description: device.description, 
  serial: device.serial, 
  asset: assetTagValue
 });

 deviceTableBody.appendChild(row); 

    });
  

  deviceDataInput.value = JSON.stringify(deviceData); 
  }

  installationTypeSelect.addEventListener('change', updateForm);

  document.addEventListener('DOMContentLoaded', () => {
    updateForm();
  });

  document.getElementById('equipmentForm').addEventListener('submit', function() { 
    const deviceData = [];
    deviceTableBody.querySelectorAll('tr').forEach(row => { 
      const sku = row.cells[1].innerText; 
      const description = row.cells[2].innerText; 
      const serial = row.querySelector('input[name="serial_number[]"]').value; 
      const asset = row.querySelector('input[name="asset_tag[]"]').value; 
      
      deviceData.push({ 
        Sku: sku, 
        description: description, 
        serial: serial, 
        asset: asset });
      });

    deviceDataInput.value = JSON.stringify(deviceData);
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
