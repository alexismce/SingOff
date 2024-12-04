function togglePatriotSize(button) {
  const row = button.closest('tr');
  const itemCell = row.cells[1];
  const descCell = row.cells[2];
  const hiddenInput = row.querySelector('input[type="hidden"]');
  if (button.textContent === '10"') {
    button.textContent = '12"';
    itemCell.textContent = 'mdt12';
    descCell.textContent = 'RMI Patriot 12" MDC';
    hiddenInput.value = 'RMI Patriot 12" MDC';
  } else {
    button.textContent = '10"';
    itemCell.textContent = 'mdt10';
    descCell.textContent = 'RMI Patriot 10" MDC';
    hiddenInput.value = 'RMI Patriot 10" MDC';
  }
}

function toggleRMHBSize(button) {
  const row = button.closest('tr');
  const itemCell = row.cells[1];
  const descCell = row.cells[2];
  const hiddenInput = row.querySelector('input[type="hidden"]');
  if (button.textContent === '12"') {
    button.textContent = '15"';
    itemCell.textContent = 'HB15';
    descCell.textContent = 'RMI RMHB 15"';
    hiddenInput.value = 'RMI RMHB 15"';
  } else {
    button.textContent = '12"';
    itemCell.textContent = 'HB12';
    descCell.textContent = 'RMI RMHB 12"';
    hiddenInput.value = 'RMI RMHB 12"';
  }
}

function togglePlateType(button) {
  const row = button.closest('tr');
  const itemCell = row.cells[1];
  const descCell = row.cells[2];
  const hiddenInput = row.querySelector('input[type="hidden"]');
  if (button.textContent === 'A') {
    button.textContent = 'B';
    itemCell.textContent = 'PLB2';
    descCell.textContent = 'RMI Installation Plate B';
    hiddenInput.value = 'RMI Installation Plate B';
  } else {
    button.textContent = 'A';
    itemCell.textContent = 'PLA2';
    descCell.textContent = 'RMI Installation Plate A';
    hiddenInput.value = 'RMI Installation Plate A';
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

  deviceSkus.forEach((sku, index) => {
    const device = allDevices.find(d => d.Sku === sku);

    const row = deviceTableBody.insertRow();
    const indexCell = row.insertCell();
    const itemCell = row.insertCell();
    const descCell = row.insertCell();
    const optionsCell = row.insertCell();
    const serialCell = row.insertCell();
    const assetCell = row.insertCell();

    indexCell.textContent = index + 1;
    itemCell.textContent = device.Sku;
    descCell.textContent = device.description;

    // Add MDT Type toggle button for relevant devices
    if (device.Sku === "mdt10" || device.Sku === "mdt12") {
      const mdtTypeDiv = document.createElement('div');
      mdtTypeDiv.classList.add('btn-group', 'btn-group-toggle');
      mdtTypeDiv.setAttribute('data-toggle', 'buttons');
      mdtTypeDiv.innerHTML = `
        <label class="btn btn-secondary active" onclick="togglePatriotSize(this)">
            <input type="hidden" name="${device.Sku}_description" value="${device.description}">
            <input type="hidden" name="${device.Sku}_mdtType" value="12">
            12"
        </label>
      `;
      optionsCell.appendChild(mdtTypeDiv);
    }

    // Add Plate Type toggle button for relevant devices
    if (device.Sku === "PLA2" || device.Sku === "PLB2") {
      const plateTypeDiv = document.createElement('div');
      plateTypeDiv.classList.add('btn-group', 'btn-group-toggle');
      plateTypeDiv.setAttribute('data-toggle', 'buttons');
      plateTypeDiv.innerHTML = `
        <label class="btn btn-secondary active" onclick="togglePlateType(this)">
            <input type="hidden" name="${device.Sku}_description" value="${device.description}">
            <input type="hidden" name="${device.Sku}_plateType" value="${device.Sku === 'PLA2' ? 'A' : 'B'}">
            ${device.Sku === 'PLA2' ? 'A' : 'B'}
        </label>
      `;
      optionsCell.appendChild(plateTypeDiv);
    }

    // Add RMHB Type toggle button for relevant devices
    if (device.Sku === "HB12" || device.Sku === "HB15") {
      const rmhbTypeDiv = document.createElement('div');
      rmhbTypeDiv.classList.add('btn-group', 'btn-group-toggle');
      rmhbTypeDiv.setAttribute('data-toggle', 'buttons');
      rmhbTypeDiv.innerHTML = `
        <label class="btn btn-secondary active" onclick="toggleRMHBSize(this)">
            <input type="hidden" name="${device.Sku}_description" value="${device.description}">
            <input type="hidden" name="${device.Sku}_rmhbType" value="12">
            12"
        </label>
      `;
      optionsCell.appendChild(rmhbTypeDiv);
    }

    // Add checkbox for VHF Radio Antenna and EMRSW1
    if (device.Sku === "VFA1" || device.Sku === "EMRSW1") {
      const checkboxDiv = document.createElement('div');
      checkboxDiv.classList.add('form-check');
      checkboxDiv.innerHTML = `
        <input class="form-check-input" type="checkbox" name="${device.Sku}_installed" id="${device.Sku}_installed">
        <label class="form-check-label" for="${device.Sku}_installed">
          Installed
        </label>
      `;
      serialCell.appendChild(checkboxDiv);
    } else {
      const serialInput = document.createElement('input');
      serialInput.type = 'text';
      serialInput.name = `${device.Sku}_serial`;
      serialInput.classList.add('form-control');
      serialCell.appendChild(serialInput);
    }

    const assetInput = document.createElement('input');
    assetInput.type = 'text';
    assetInput.name = `${device.Sku}_asset`;
    assetInput.classList.add('form-control');
    assetCell.appendChild(assetInput);

    if (disabledAssetTags.includes(device.description)) {
      assetInput.disabled = true;
    }
  });
}

installationTypeSelect.addEventListener('change', updateForm);

// Initial form update
updateForm();
