document.addEventListener('DOMContentLoaded', function() {
    const installerCanvas = document.getElementById('installer-signature-pad');
    const calfireCanvas = document.getElementById('calfire-signature-pad');
    const installerSignaturePad = new SignaturePad(installerCanvas);
    const calfireSignaturePad = new SignaturePad(calfireCanvas);

    const installerSignatureDataInput = document.querySelector('input[name="installer_signature_data"]');
    const calfireSignatureDataInput = document.querySelector('input[name="calfire_signature_data"]');

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

    // Prepopulate Date
    const dateField = document.getElementById('date');
    if (dateField) {
        const today = new Date();
        today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
        const localDate = today.toISOString().split('T')[0];
        dateField.value = localDate;
    }
});
