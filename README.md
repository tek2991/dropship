# Dropship
Server setup guide on Ubuntu 20.04 LTS (ARM64 AWS Graviton2 EC2)

This will setup a LEMP stack with PostgreSQL as the DBMS, MySql is also fully compitable.
## PostgreSQL
 - Install: `sudo apt install postgresql postgresql-contrib`
 - *Status:* `sudo systemctl status postgresql` or `ps aux | grep postgres`
  ##### Setup Database
 - Switch to PostgreSQL: `sudo -i -u postgres`
 - Create new role: `createuser --interactive` (***recommended:** select 'n' for all prompts*)
 - Create new DB: `createdb database_name-same-as-user`
 - Open PostgreSQL prompt: `psql`
 - Set user password (in PostgreSQL prompt): `ALTER USER user_name PASSWORD 'password';`
## PHP 8.0 fpm
 - Install prerequisite: `sudo apt install software-properties-common`
 - Add PPA: `sudo add-apt-repository ppa:ondrej/php`
 - Update: `sudo apt update`
 - Install for Nginx: `sudo apt install php8.0-fpm`
 - *Status:* `systemctl status php8.0-fpm` or `ps aux | grep php-fpm`
### PHP extensions
*Note: PHP version number may be omitted as applicable*
 - php8.0-bcmath: `sudo apt install php8.0-bcmath`
 - php8.0-curl: `sudo apt install php8.0-curl`
 - php8.0-gd: `sudo apt install php8.0-gd`
 - php8.0-mbstring: `sudo apt install php8.0-mbstring`
 - php8.0-pgsql: `sudo apt install php8.0-pgsql`
 - php8.0-xml: `sudo apt install php8.0-xml`
 - php8.0-zip:  `sudo apt install php8.0-zip`
 - php8.0-intl: `sudo apt install php8.0-intl`
##### *Install all:* `sudo apt install php8.0-bcmath php8.0-curl php8.0-gd php8.0-mbstring php8.0-pgsql php8.0-xml php8.0-zip php8.0-intl`
## NGINX
 - Install: `sudo apt install nginx`
 - *Status:* `sudo systemctl status nginx` or `ps aux | grep nginx`
### Configure Nginx
 - Find PHP 8.0 fpm socket: `find / -name *fpm.sock`
 - *Result (may vary):* `/run/php/php8.0-fpm.sock`
 - Create new config file: `sudo nano /etc/nginx/sites-available/site_name`
 - Create symbolic link: `sudo ln -s /etc/nginx/sites-available/site_name /etc/nginx/sites-enabled/`
 - Test configuration: `sudo nginx -t`
 - Reload Nginx: `sudo systemctl reload nginx`
## Package managers
### Composer for PHP
 - Follow guide at: [https://getcomposer.org/download/](https://getcomposer.org/download/)
 - ***Note-*** Install composer globally: `sudo mv composer.phar /usr/local/bin/composer`
### NPM (Node.js)
 - Add Node.js LTS (v14.x): `curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -`
 - Install: `sudo apt-get install -y nodejs`
## Laravel configuration
 - Create .env file: `sudo cp .env.example .env`
 - Setup .env as required.
 - Composer intall: `composer install`
 - NPM install: `npm install`
 - Build for production: `npm run production`
 - Generate App key: `php artisan key:generate`
 - Link storage: `php artisan storage:link`
## Laravel Folder Permissions
 ### Setup with user as owner: 
 - Nagivate to root directory: `cd /var/www/site_name`
- Set User as owner & web-server as group: `sudo chown -R $USER:www-data .`
- Set file permissions: `sudo find . -not -path "./vendor/*" -not -path "./node_modules/*" -type f -exec chmod 664 {} \;`
- Set directory permissions: `sudo find . -not -path "./vendor/*" -not -path "./node_modules/*" -type d -exec chmod 775 {} \;`
- give the webserver the rights to read and write to storage and cache: `sudo chgrp -R www-data storage bootstrap/cache` and `sudo chmod -R ug+rwx storage bootstrap/cache`