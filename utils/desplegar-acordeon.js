const dias = document.querySelectorAll('.boton-opciones');
    dias.forEach(dia => {
      dia.addEventListener('click', () => {
        const contenido = dia.nextElementSibling;
        const abierto = contenido.style.display === 'block';
        // Cierra todos los contenidos
        document.querySelectorAll('.contenido-dia').forEach(c => c.style.display = 'none');
        // Abre el seleccionado
        if (!abierto) contenido.style.display = 'block';
      });
    });