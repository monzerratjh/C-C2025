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
                <input type="time" name="horario_adscripto_usuario" id="horario_entrada_adscripto_usuario" >
                <label>Horario de Salida:</label>
                <input type="time" name="horario_adscripto_usuario" id="horario_salida_adscripto_usuario" >
                <input type="text" name="caracter_cargo_adscripto" placeholder="Cargo" id="caracter_cargo_adscripto" >
                `;
            } else if(select_cargo_usuario.value == 'Secretario') {
                extra_inputs.innerHTML = `
                <label>Horario de Entrada:</label>
                <input type="time" name="horario_adscripto_usuario" id="horario_entrada_adscripto_usuario" >
                <label>Horario de Salida:</label>
                <input type="time" name="horario_adscripto_usuario" id="horario_salida_adscripto_usuario" >
                <input type="number" name="cargo_secretario_usuario" placeholder="Cargo de Secretario" id="cargo_secretario_usuario" >
                `;
            } else if(select_cargo_usuario.value == '') {
                //no sé
            }
        } );