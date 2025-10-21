!/bin/bash #indica que el script se ejecuta usando Bash

# ================================
# CONFIGURACIÓN DE LA BASE DE DATOS
# ================================
DB_HOST="localhost"
DB_USER="CoffeeAndCode"
DB_PASS="coffeAndCode2025"
DB_NAME="db_CoffeeAndCode" 

# ================================
# FUNCIONES DE VALIDACIÓN
# ================================

validar_cedula() {
    local ci="$1"
    # Verifica que tenga 8 caracteres
    if [ ${#ci} -ne 8 ]; then
        echo "ERROR: La cédula debe tener 8 dígitos."
        return 1
    fi
}

validar_telefono() {
    local tel="$1"
     # Verifica que tenga 9 caracteres
    if [ ${#tel} -ne 9 ]; then
        echo "ERROR: El teléfono debe tener 9 dígitos."
        return 1
    fi
    return 0
}

usuario_existe() {
    local campo="$1"  # 'ci_usuario' o 'gmail_usuario'
    local valor="$2"
    if mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -sse \
       "SELECT COUNT(*) FROM usuario WHERE $campo='$valor';" | grep -qv '^0$'; then
        return 0  # Existe
    else
        return 1  # No existe
    fi
}

validar_usuario_id() {
    local id="$1"
    if mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -sse \
       "SELECT COUNT(*) FROM usuario WHERE id_usuario=$id;" | grep -q '^1$'; then
        return 0  # Existe
    else
        echo "ERROR: No existe usuario con ID $id."
        return 1
    fi
}

# ================================
# FUNCIONES CRUD
# ================================

insertar_usuario() {
    echo "=== Insertar Usuario ==="
    read -p "Nombre: " nombre
    read -p "Apellido: " apellido

    # Validar Gmail único
    while true; do
        read -p "Gmail: " gmail
        if usuario_existe "gmail_usuario" "$gmail"; then
            echo "ERROR: Ya existe un usuario con este Gmail. Intente otro."
        else
            break
        fi
    done

    # Validar teléfono
    while true; do
        read -p "Teléfono (9 dígitos): " telefono
        validar_telefono "$telefono" && break
    done

    read -p "Cargo (Secretario/Docente/Adscripto): " cargo

    # Validar cédula única y correcta
    while true; do
        read -p "Cédula (8 dígitos): " ci
        validar_cedula "$ci" && ! usuario_existe "ci_usuario" "$ci" && break || echo "ERROR: La cédula ya existe o es inválida."
    done

    read -s -p "Contraseña: " contrasenia
    echo ""

    mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e \
    "INSERT INTO usuario (nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, cargo_usuario, ci_usuario, contrasenia_usuario) VALUES ('$nombre','$apellido','$gmail','$telefono','$cargo',$ci,'$contrasenia');"

    echo "Usuario insertado correctamente."
}

listar_usuarios() {
    echo "=== Lista de Usuarios ==="
    mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e \
    "SELECT id_usuario, nombre_usuario, apellido_usuario, gmail_usuario, telefono_usuario, cargo_usuario, ci_usuario FROM usuario;"
}

eliminar_usuario() {
    echo "=== Eliminar Usuario ==="
    read -p "Ingrese ID del usuario a eliminar: " id_usuario
    validar_usuario_id "$id_usuario" || return

    read -p "¿Está seguro que desea eliminar este usuario? (s/n): " confirm
    if [[ "$confirm" =~ ^[sS]$ ]]; then
        mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e \
        "DELETE FROM usuario WHERE id_usuario=$id_usuario;"
        echo "Usuario eliminado."
    else
        echo "Operación cancelada."
    fi
}

modificar_usuario() {
    echo "=== Modificar Usuario ==="
    read -p "Ingrese ID del usuario a modificar: " id_usuario
    validar_usuario_id "$id_usuario" || return

    read -p "Nuevo nombre: " nombre
    read -p "Nuevo apellido: " apellido

    # Validar Gmail único si cambia
    while true; do
        read -p "Nuevo Gmail: " gmail
        if usuario_existe "gmail_usuario" "$gmail"; then
            echo "ERROR: Ya existe un usuario con este Gmail. Intente otro."
        else
            break
        fi
    done

    # Validar teléfono
    while true; do
        read -p "Nuevo teléfono (9 dígitos): " telefono
        validar_telefono "$telefono" && break
    done

    read -p "Nuevo cargo: " cargo

    # Validar cédula única y correcta si cambia
    while true; do
        read -p "Nueva cédula (8 dígitos): " ci
        validar_cedula "$ci" && ! usuario_existe "ci_usuario" "$ci" && break || echo "ERROR: La cédula ya existe o es inválida."
    done

    read -s -p "Nueva contraseña: " contrasenia
    echo ""

    mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e \
    "UPDATE usuario SET nombre_usuario='$nombre', apellido_usuario='$apellido', gmail_usuario='$gmail', telefono_usuario='$telefono', cargo_usuario='$cargo', ci_usuario=$ci, contrasenia_usuario='$contrasenia' WHERE id_usuario=$id_usuario;"

    echo "Usuario modificado correctamente."
}

# ================================
# MENÚ PRINCIPAL
# ================================
while true; do
    echo "=============================="
    echo "       MENÚ ABM USUARIOS      "
    echo "=============================="
    echo "1. Insertar un nuevo registro"
    echo "2. Eliminar un registro"
    echo "3. Modificar un registro"
    echo "4. Listar usuarios"
    echo "5. Salir"
    read -p "Seleccione una opción: " opcion

    case $opcion in
        1) insertar_usuario ;;
        2) eliminar_usuario ;;
        3) modificar_usuario ;;
        4) listar_usuarios ;;
        5) echo "Saliendo..."; exit 0 ;;
        *) echo "Opción inválida. Intente nuevamente." ;;
    esac
done
