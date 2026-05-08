# Usa la imagen de Ubuntu 20.04 como base
FROM ubuntu:20.04

# Actualiza los paquetes disponibles e instala las herramientas necesarias
RUN apt-get update && \
    apt-get install -y \
    curl \
    wget \
    git \
    unzip \
    software-properties-common

# Instala PHP y las extensiones necesarias para Laravel
RUN add-apt-repository ppa:ondrej/php && \
    apt-get update && \
    apt-get install -y \
    php8.3 \
    php8.3-cli \
    php8.3-common \
    php8.3-mysql \
    php8.3-sqlite \
    php8.3-xml \
    php8.3-mbstring \
    php8.3-json \
    php8.3-curl \
    php8.3-zip \
    php8.3-gd \
    php8.3-imagick

# Instala NVM (Node Version Manager)
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash

# Recarga el entorno para que NVM esté disponible
SHELL ["/bin/bash", "--login", "-c"]

# Instala la última versión de Node.js y npm utilizando NVM
RUN nvm install node

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instala NGINX
RUN apt-get install -y nginx

# Configura NGINX para el proyecto Laravel
COPY files/xPanel /etc/nginx/sites-available/xPanel

# Instala MariaDB
RUN apt-get install -y mariadb-server

# Crear usuario con privilegios en MariaDB
RUN service mysql start && \
    mysql -e "CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'password';" && \
    mysql -e "GRANT ALL PRIVILEGES ON * . * TO 'laravel_user'@'localhost';" && \
    mysql -e "FLUSH PRIVILEGES;"

# Exponer puertos para NGINX y MariaDB
EXPOSE 80 3306

# Inicia NGINX y MariaDB
CMD service nginx start && service mysql start && /bin/bash