# xPanel Services

## Overview

This guide provides step-by-step instructions for setting up a development environment with Nginx and Apache for xPanel. It covers installation procedures, necessary permissions, and steps to get your website up and running.

## Prerequisites

- PHP >= 8.2
- Composer
- Laravel Installer
- Node & NPM
- Nginx or Apache
- MySQL

## Installation

### Laravel

1. **Install Composer:**

   ```
   bash
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   ```

2. **Install Composer Libreries**
    ```
    composer install
    ```

### Nginx

1. **Install Nginx:**

   ```
   bash
   sudo apt update
   sudo apt install nginx
   ```

2. **Configure Nginx**
    - Copy your site configuration file into /etc/nginx/sites-available/.
    - Create a symbolic link to /etc/nginx/sites-enabled/
    ```
    sudo ln -s /etc/nginx/sites-available/your-site /etc/nginx/sites-enabled/
    sudo systemctl restart nginx
    ```

### Apache

1. **Install Apache:**
```
sudo apt update
sudo apt install apache2
```

2. **Enable required modules:**
```
sudo a2enmod rewrite
sudo a2enmod php7.x  # Replace '7.x' with your installed PHP version
```

3. **Restart Apache:**
```
sudo systemctl restart apache2
```

### Permission Setup

1. **Permissions in Laravel:**
Ensure Laravel has write permissions on storage and cache folders:
```
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Getting Started
1. **Configure Laravel:**

- Copy the .env.example file to .env.
- Configure your environment variables.
- Run:
```
composer install
php artisan key:generate
```

### Install Node Version Manager

## Installation

### Using cURL

1. **Install NVM:**

   Run the following command in your terminal:

   ```
   bash
   curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash
   ```
   This command will download and execute the NVM installation script.

2. **Verify Installation:**

To verify that NVM was installed correctly, close and reopen your terminal (or run source ~/.bashrc or source ~/.bash_profile), then run:
``` 
command -v nvm
```
## Usage
### Installing Node.js
1. **List Available Node.js Versions:**
```
nvm ls-remote
```

2. **Install a Specific Version:**
```
nvm install <version>
```
Replace <version> with the desired Node.js version (e.g., 14.17.0).

3. **Use a Specific Version:**
After installing multiple versions, you can switch between them using:
```
nvm use <version>
```

## Support
For any issues or question related to xPanel, please open a ticket in Discord or open an issue in github to fix the problem. 
