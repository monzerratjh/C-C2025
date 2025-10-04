//Creamos funcion
document.getElementById('grupoInput').addEventListener('change', function() {
     //dentro de la funcion obtenemos los valores del input y se eliminan los espacios que sobren
      const inputValue = this.value.trim();

      // Definimos las páginas a las que redirigir según el grupo seleccionado
      const validValues = {
        '1° MD': '1MD.php',
        '2° MD': '2MD.php', 
        '3° MD': '3MD.php',
      };

      // Verificamos si el valor ingresado es uno de los valores válidos
      if (validValues[inputValue]) {
        window.location.href = validValues[inputValue];  // Redirigimos al usuario
      } else {
        console.log("Opción no válida");  // Si no es válido, mostramos un mensaje en la consola
      }
    });
