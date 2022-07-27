# FROM node:18-alpine3.15 AS assets-build

FROM node:16.13.1-alpine3.13 AS assets-build


ARG PPROXY=""
ARG PSPROXY=""
ARG SSH_PRIVATE_KEY=""
ARG SSH_KNOWN_HOSTS=""
ARG RELEASE_HOST=""
ARG RELEASE_KEY=""

# ENV VERSION=v16.13.0 NPM_VERSION=8.1.0

RUN apk update && apk upgrade && \
    apk add --no-cache bash git openssh


WORKDIR /var/www/html
COPY . /var/www/html/

# Add git host key:
RUN if [ -z "$SSH_PRIVATE_KEY" ]; then \
    echo "SSH_PRIVATE_KEY not set" \
  else \
    mkdir -p ~/.ssh && chmod 700 ~/.ssh \
    echo "$RELEASE_KEY" | tr -d '\r' > ~/.ssh/id_rsa \
    echo "$RELEASE_HOST" >> ~/.ssh/known_hosts \
    chmod 644 ~/.ssh/known_hosts && chmod 400 ~/.ssh/id_rsa \
  fi
### 

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
