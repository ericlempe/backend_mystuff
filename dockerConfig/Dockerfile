FROM wyveo/nginx-php-fpm:php81

#instalação pacotes
RUN apt-get update
RUN apt install php-dev curl -y

#nodejs
 RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

#Configurações adicionais nginx e php
ADD nginx.conf /etc/nginx/conf.d/default.conf
ADD custom.ini /etc/php/8.1/fpm/conf.d/custom.ini

WORKDIR /www/html
