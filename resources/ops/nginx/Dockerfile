# FROM node:18-alpine3.15 AS assets-build

FROM node:16.13.1-alpine3.13 AS assets-build

# ENV VERSION=v16.13.0 NPM_VERSION=8.1.0

ARG PPROXY=""
ARG PSPROXY=""

WORKDIR /var/www/html
COPY . /var/www/html/

RUN git submodule update --init --recursive

RUN if [ -z "$PPROXY" ]; then \
    echo "http proxy not set" \
  else \
    npm config set proxy ${PPROXY} \
  fi

RUN if [ -z "$PSPROXY" ]; then \
    echo "http proxy not set" \
  else \
    npm config set https-proxy ${PSPROXY} \
  fi


RUN npm ci
RUN npm run build

FROM nginx:1.19-alpine AS nginx

COPY ./resources/ops/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=assets-build /var/www/html/public /var/www/html/
