#!/bin/bash

# CONFIGURACIÓN DE LA BASE DE DATOS
DB_HOST="localhost"  
DB_USER="CoffeeAndCode"
DB_PASS="coffeandcode2025"   
DB_NAME="db_CoffeeAndCode"     

#FUNCIÓN PARA INSERTAR USUARIO 
insertar_usuario() {
    echo "=== Insertar Usuario ==="

    #Guardar una entrada en una variable
    read -p "Nombre: " nombre 
    read -p "Apellido: " apellido
    read -p "Gmail: " gmail
    read -p "Teléfono (9 dígitos): " telefono
    read -p "Cargo (Secretario/Docente/Adscripto): " cargo
    read -p "Cédula: " ci
    read -s -p "Contraseña: " contrasenia   # "-s" oculta lo que se escribe
    echo ""

    # Se ejecuta una sentencia SQL para insertar el nuevo usuario
    # comandos para conectarse a una base de datos MySQL desde Bash
    #con las variables de arriba
    mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" <<EOF
INSERT INTO usuario (nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, cargo_usuario, ci_usuario, contrasenia_usuario)
VALUES ('$nombre','$apellido','$gmail','$telefono','$cargo',$ci,'$contrasenia');
EOF

    echo "Usuario insertado correctamente."
}

# FUNCIÓN PARA ELIMINAR USUARIO 
eliminar_usuario() {
    echo "=== Eliminar Usuario ==="
    read -p "Ingrese el ID del usuario a eliminar: " id_usuario   # Se pide el ID del usuario y se guarda en id_usuario

    # Se ejecuta una sentencia SQL para eliminar al usuario con ese ID
    mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e \
    "DELETE FROM usuario WHERE id_usuario=$id_usuario;"

    echo "Usuario eliminado."
}

# FUNCIÓN PARA MODIFICAR USUARIO
modificar_usuario() {
    echo "=== Modificar Usuario ==="
    read -p "Ingrese el ID del usuario a modificar: " id_usuario

    # Primero traemos los valores actuales de la base
    read nombre_actual apellido_actual gmail_actual telefono_actual cargo_actual ci_actual <<< $(mysql -N -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SELECT nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, cargo_usuario, ci_usuario FROM usuario WHERE id_usuario=$id_usuario;")

    # Pedimos los nuevos valores. Si no se ingresa nada, se mantiene el valor actual
    read -p "Nuevo nombre [$nombre_actual]: " nombre
    nombre=${nombre:-$nombre_actual}  # si está vacío, usa valor actual

    read -p "Nuevo apellido [$apellido_actual]: " apellido
    apellido=${apellido:-$apellido_actual}

    read -p "Nuevo Gmail [$gmail_actual]: " gmail
    gmail=${gmail:-$gmail_actual}

    read -p "Nuevo teléfono (9 dígitos) [$telefono_actual]: " telefono
    telefono=${telefono:-$telefono_actual}

    read -p "Nuevo cargo (Secretario/Docente/Adscripto) [$cargo_actual]: " cargo
    cargo=${cargo:-$cargo_actual}

    read -p "Nueva cédula [$ci_actual]: " ci
    ci=${ci:-$ci_actual}

    read -s -p "Nueva contraseña [no mostrada]: " contrasenia
    echo ""
    # Si no ingresó nada, se mantiene la contraseña anterior
    if [ -z "$contrasenia" ]; then
        contrasenia=$(mysql -N -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SELECT contrasenia_usuario FROM usuario WHERE id_usuario=$id_usuario;")
    fi

    # Ejecutamos el UPDATE con los valores finales
    mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e \
    "UPDATE usuario SET nombre_usuario='$nombre', apellido_usuario='$apellido', gmail_usuario='$gmail', telefono_usuario='$telefono', cargo_usuario='$cargo', ci_usuario=$ci, contrasenia_usuario='$contrasenia' WHERE id_usuario=$id_usuario;"

    echo "Usuario modificado."
}

# FUNCIÓN PARA LISTAR USUARIOS
listar_usuarios() {
    echo "=== Lista de Usuarios ==="

    # Se ejecuta una consulta SQL SELECT para mostrar los usuarios existentes
    mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e \
    "SELECT id_usuario, nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, cargo_usuario, ci_usuario FROM usuario;"
}

# MENÚ PRINCIPAL

# Un bucle infinito que muestra el menú hasta que el usuario elige la opción de salir
while true; do
    # Muestra el menú con las opciones disponibles
    echo "=================================="
    echo "        MENÚ ABM USUARIO         "
    echo "=================================="
    echo "1. Insertar un nuevo registro"
    echo "2. Eliminar un registro"
    echo "3. Modificar un registro"
    echo "4. Listar usuarios"
    echo "5. Salir"
    
    read -p "Seleccione una opción: " opcion # Se pide al usuario que elija una opción y la guarda

    # Se evalúa la opción seleccionada con una estructura CASE
    case $opcion in
        1) insertar_usuario ;; 
        2) eliminar_usuario ;; 
        3) modificar_usuario ;; 
        4) listar_usuarios ;;  
        5) echo "Saliendo..."; exit 0 ;;  
        *) echo "Opción inválida. Intente nuevamente." ;; 
    esac
done
