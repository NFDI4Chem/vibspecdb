FROM node:16.13.1-alpine3.13 AS assets-build


ARG PPROXY=""
ARG PSPROXY=""


# ENV VERSION=v16.13.0 NPM_VERSION=8.1.0

WORKDIR /var/www/html
COPY . /var/www/html/

RUN npm config set proxy ${PPROXY} && npm config set https-proxy ${PSPROXY}


RUN npm ci
RUN npm run build