#!/usr/bin/env bash
# ======================================================= #
# config.sh - Configuración para Vagrant LAMP con Debut
#
# Archivo de configuración automática para la box 
# Ubuntu 16.04 en Vagrant.
#
# Autor: Daniel Martínez <danmnez.me>
# Creado: 14.09.2016
# Actualizado: 20.11.2016
# ======================================================= #

# ======================================================= #
# Variables y funciones globales
# ======================================================= #
PROJECTFOLDER='www' # Nombre del directorio para los proyectos.
MYSQL_PASSWORD='secret' # Contraseña de MySQL.
DATABASE_SQL='ejemplo_mysql.sql' # Base de datos.
GIT_REPOS='https://github.com/DanMnez/Debut.git' # Repositorios del proyecto.

update() {
	sudo apt-get update
}

# ======================================================= #
# Actualizamos el sistema e instalamos utilidades
# ======================================================= #
update
sudo apt-get upgrade -y
sudo apt-get install zip unzip -y

# ======================================================= #
# Instalación de apache2
# ======================================================= #
sudo apt-get install apache2 -y

# ======================================================= #
# Instalación de php 7 
# ======================================================= #
sudo apt-get install python-software-properties -y
update
sudo apt-get install -y php libapache2-mod-php php-mcrypt php-mysql \
                        php-curl php-cli

# ======================================================= #
# Instalación de mysql 5.7 
# ======================================================= #
export DEBIAN_FRONTEND=noninteractive # Forzamos al sistema a no tener interacción manual.
update

sudo debconf-set-selections <<< "mysql-server-5.7 mysql-server/root_password password $MYSQL_PASSWORD"
sudo debconf-set-selections <<< "mysql-server-5.7 mysql-server/root_password_again password $MYSQL_PASSWORD"
sudo apt-get install mysql-server -y # Instalar MySQL

# ======================================================= #
# Creación del directorio del proyecto
# ======================================================= #
sudo mkdir "/var/www/html/${PROJECTFOLDER}"

# ======================================================= #
# Creación del virtualhost
# ======================================================= #
VHOST=$(cat <<EOF
<VirtualHost *:80>
    DocumentRoot "/var/www/html/${PROJECTFOLDER}/public"
    <Directory "/var/www/html/${PROJECTFOLDER}/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF
)
echo "${VHOST}" > /etc/apache2/sites-available/000-default.conf

# ======================================================= #
# Habilita el módulo reescritura para urls amigables
# Reinicia apache
# ======================================================= #
sudo a2enmod rewrite
service apache2 restart

# ======================================================= #
# Elimina el index.html por defecto de apache
# ======================================================= #
sudo rm "/var/www/html/index.html"

# ======================================================= #
# Instala Git
# ======================================================= #
sudo apt-get install -y git

# ======================================================= #
# Clona el repositorio 
# ======================================================= #
sudo git clone "${GIT_REPOS}" "/var/www/html/${PROJECTFOLDER}"

# ======================================================= #
# Instalación de Composer
# ======================================================= #
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# ======================================================= #
# Instalación de los paquetes incluidos en composer
# ======================================================= #
cd "/var/www/html/${PROJECTFOLDER}"
composer install

# ======================================================= #
# Carga la base de datos
# ======================================================= #
sudo mysql -h "localhost" -u "root" "-p${MYSQL_PASSWORD}" < "/var/www/html/${PROJECTFOLDER}/_database/${DATABASE_SQL}"

# ======================================================= #
# Mensaje de fin
# ======================================================= #
echo -e "\nVagrant LAMP con Debut ha sido instalado. Enjoy!"
