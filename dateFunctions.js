function setCurrentDate() {
  const dateField = document.getElementById('date');
  if (dateField) {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    dateField.value = `${year}-${month}-${day}`;
  }
}

// Call the function to set the current date when the page loads
window.addEventListener('load', setCurrentDate);
