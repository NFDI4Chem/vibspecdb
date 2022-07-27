# FROM node:18-alpine3.15 AS assets-build

FROM node:16.13.1-alpine3.13 AS assets-build

# ENV VERSION=v16.13.0 NPM_VERSION=8.1.0

ARG http_proxy
ARG https_proxy

WORKDIR /var/www/html

COPY . /var/www/html/

RUN npm config set proxy ${http_proxy}
RUN npm config set ${https_proxy}

RUN npm ci
RUN npm i -g lerna
RUN npm run lerna
RUN npm run build

FROM nginx:1.19-alpine AS nginx

COPY ./resources/ops/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=assets-build /var/www/html/public /var/www/html/
