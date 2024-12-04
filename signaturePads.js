const signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
  backgroundColor: 'rgba(255, 255, 255, 0)',
  penColor: 'rgb(0, 0, 0)'
});

const calfireSignaturePad = new SignaturePad(document.getElementById('calfire-signature-pad'), {
  backgroundColor: 'rgba(255, 255, 255, 0)',
  penColor: 'rgb(0, 0, 0)'
});

document.getElementById('clear').addEventListener('click', () => {
  signaturePad.clear();
  calfireSignaturePad.clear();
});

// Ensure touch screen support
document.getElementById('signature-pad').addEventListener('touchstart', (event) => {
  event.preventDefault();
  signaturePad.on();
});

document.getElementById('calfire-signature-pad').addEventListener('touchstart', (event) => {
  event.preventDefault();
  calfireSignaturePad.on();
});
