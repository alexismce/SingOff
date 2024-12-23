document.addEventListener('DOMContentLoaded', () => {
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
      });

      deviceDataInput.value = JSON.stringify(deviceData); 
  }

  installationTypeSelect.addEventListener('change', updateForm);

  updateForm(); // Initial call to populate the form
});
