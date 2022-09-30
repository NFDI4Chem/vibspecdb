FROM nginx:1.19-alpine AS nginx

WORKDIR /var/www/html

COPY ./resources/ops/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY public ./
