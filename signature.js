const signaturePad = new SignaturePad(document.getElementById('signature-pad'));
const calfireSignaturePad = new SignaturePad(document.getElementById('calfire-signature-pad'));

document.getElementById('clear').addEventListener('click', () => {
  signaturePad.clear();
  calfireSignaturePad.clear();
});
