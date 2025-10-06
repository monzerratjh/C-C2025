select_cargo_usuario = document.getElementById('usuarioTipo');
        const extra_inputs = document.getElementById('extra-inputs');

        select_cargo_usuario.addEventListener('change', () => {
            if(select_cargo_usuario.value == 'Docente') {
                extra_inputs.innerHTML = `
                <input type="number" name="grado_docente_usuario" placeholder="Grado de docente" id="grado_docente_usuario" >
                `;
            } else if (select_cargo_usuario.value == 'Adscripto') {
                extra_inputs.innerHTML = `
                <input type="number" name="cantidad_grupo_adscripto" placeholder="Cantidad de grupos a cargo" id="cantidad_grupo_adscripto" >
                <label>Horario de Entrada:</label>
                <input type="time" name="horario_entrada_adscripto" id="horario_entrada_adscripto">
                <label>Horario de Salida:</label>
                <input type="time" name="horario_salida_adscripto" id="horario_salida_adscripto" >
                <input type="text" name="caracter_cargo_adscripto" placeholder="Cargo" id="caracter_cargo_adscripto" >
                `;
            } else if(select_cargo_usuario.value == 'Secretario') {
                extra_inputs.innerHTML = `
                <label>Horario de Entrada:</label>
                <input type="time" name="horario_entrada_secretario" id="horario_entrada_secretario" >
                <label>Horario de Salida:</label>
                <input type="time" name="horario_salida_secretario" id="horario_salida_secretario" >
                <input type="number" name="grado_secretario" placeholder="Grado de Secretario" id="grado_secretario" >
                `;
            } else if(select_cargo_usuario.value == '') {
                //no sé
            }
        } );