//Creamos funcion
document.getElementById('grupoInput').addEventListener('change', function() {
     //dentro de la funcion obtenemos los valores del input y se eliminan los espacios que sobren
      const inputValue = this.value.trim();

      // Definimos las páginas a las que redirigir según el grupo seleccionado
      const validValues = {
        '1° MD': 'horarios1MD.html',
        '2° MD': 'horarios2MD.html', 
        '3° MD': 'horarios3MD.html',
        '3° MB': 'horarios3MB.html'  
      };

      // Verificamos si el valor ingresado es uno de los valores válidos
      if (validValues[inputValue]) {
        window.location.href = validValues[inputValue];  // Redirigimos al usuario
      } else {
        console.log("Opción no válida");  // Si no es válido, mostramos un mensaje en la consola
      }
    });