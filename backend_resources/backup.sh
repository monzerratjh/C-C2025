#!/bin/bash

# ======== CONFIGURACIÓN ========
USER="CoffeeAndCode"                      # Usuario de MySQL
PASS="coffeandcode2025"             # Contraseña de MySQL
DB="db_CoffeeAndCode"        # Nombre de la base de datos
BACKUP_DIR="/var/www/CoffeeAndCode/backups"  # Carpeta donde se guardan los backups
DATE=$(date +%Y-%m-%d_%H-%M)     # Fecha y hora actual

# ======== CREAR CARPETA SI NO EXISTE ========
mkdir -p "$BACKUP_DIR"

# ======== HACER BACKUP ========
mysqldump -u $USER -p$PASS $DB > "$BACKUP_DIR/backup_$DATE.sql"

# ======== CALCULAR TABLAS EXPORTADAS ========
TABLES_EXPORTED=$(grep -c "CREATE TABLE" "$BACKUP_DIR/backup_$DATE.sql")

# ======== COMPRIMIR ========
tar -czf "$BACKUP_DIR/backup_$DATE.tar.gz" -C "$BACKUP_DIR" "backup_$DATE.sql"

# ======== CALCULAR TAMAÑO DEL ARCHIVO ========
SIZE=$(du -h "$BACKUP_DIR/backup_$DATE.tar.gz" | cut -f1)


# ======== MENSAJE FINAL ========
echo "Tamaño del backup: $SIZE"
echo "Tablas exportadas: $TABLES_EXPORTED"
echo "Backup completado: $BACKUP_DIR/backup_$DATE.tar.gz"

# ===============================================
# CONFIGURACIÓN DEL CRON PARA BACKUP AUTOMÁTICO
# ===============================================

# DETALLES DE LA PROGRAMACIÓN:

#Se debe escribir el siguiente codigo
#crontab -e
#para que se abra la configuración del programador de tareas (cron)

# y se escribe al final del archivo
# 0 2 1 */3 * /var/www/CoffeeAndCode/backup.sh


# esto dice que la programacion se realizara al minuto 0, hora 2 y el rpimer dia del mes
#  */3 * esto indica que se hace cada tres meses


# RESULTADO:
# El script se ejecutará el 1 de enero, abril, julio y octubre a las 2:00 a.m.
#

# Para comprobar que quedo funcionando:
# crontab -l

