FROM daocloud.io/piresdev/nginx-php-laravel:v1.0.0

RUN docker-php-ext-install bcmath

COPY app /var/www/html/app
COPY artisan /var/www/html/artisan
COPY bootstrap /var/www/html/bootstrap
COPY composer.json /var/www/html/composer.json
COPY config /var/www/html/config
COPY database /var/www/html/database
COPY phpci.yml /var/www/html/phpci.yml
COPY public /var/www/html/public
COPY resources /var/www/html/resources
COPY server.php /var/www/html/server.php
COPY tests /var/www/html/tests

RUN chmod -R 777 bootstrap/cache
RUN mkdir -pm777 storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs
RUN composer install
