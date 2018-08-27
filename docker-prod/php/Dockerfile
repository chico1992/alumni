FROM php:apache


ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN apt-get update
RUN apt-get install git zlib1g-dev -y
RUN docker-php-ext-install pdo_mysql mbstring bcmath zip
RUN a2enmod rewrite

RUN echo "APP_ENV=prod" >> /etc/apache2/envvars

WORKDIR /var/www/


RUN git clone https://github.com/chico1992/alumni.git
RUN mv /var/www/alumni/alumni/* /var/www/html/
RUN rm -Rf /var/www/alumni
WORKDIR /var/www/html

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

COPY .env /var/www/html/.env

RUN ./composer.phar install
RUN rm ./composer.phar

COPY /entrypoint.sh /

ENTRYPOINT bash /entrypoint.sh
