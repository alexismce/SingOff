const { jsPDF } = window.jspdf;

document.getElementById('equipmentForm').addEventListener('submit', (event) => {
  event.preventDefault();
  const form = document.getElementById('equipmentForm');
  const formData = new FormData(form);

  const doc = new jsPDF();
  let y = 10;

  doc.setFontSize(12);
  doc.text("Equipment Installation Form", 10, y);
  y += 10;

  formData.forEach((value, key) => {
    if (key !== 'signature' && key !== 'calfire_signature') {
      doc.text(`${key}: ${value}`, 10, y);
      y += 10;
    }
  });

  // Add installer signature
  const imgData = signaturePad.toDataURL('image/png');
  doc.addImage(imgData, 'PNG', 10, y, 180, 60);
  y += 70;

  // Add CalFire officer signature
  const calfireImgData = calfireSignaturePad.toDataURL('image/png');
  doc.addImage(calfireImgData, 'PNG', 10, y, 180, 60);

  doc.save("installation_form.pdf");
});
