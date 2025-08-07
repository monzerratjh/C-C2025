// Código para redireccionar según grupo seleccionado
document.getElementById('grupoInput').addEventListener('change', function () {
  const inputValue = this.value.trim();

  const validValues = {
    '1° MD': 'horarios1MD.html',
    '2° MD': 'horarios2MD.html',
    '3° MD': 'horarios3MD.html',
    '3° MB': 'horarios3MB.html'
  };

  if (validValues[inputValue]) {
    window.location.href = validValues[inputValue];
  } else {
    alert('Grupo no válido');
  }
});
