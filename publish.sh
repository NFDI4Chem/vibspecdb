#!/bin/sh

DOCKER_USERNAME=docker.photonicdata.science

FPM_NAME=serviceapp_laravel_fpm
FPM_TAG=$(sed -n 2p version.txt)

NGINX_NAME=serviceapp_nginx
NGINX_TAG=$(sed -n 4p version.txt)

docker-compose build

docker image tag serviceapp_php:latest ${DOCKER_USERNAME}/${FPM_NAME}:${FPM_TAG}
docker image tag serviceapp_nginx:latest ${DOCKER_USERNAME}/${NGINX_NAME}:${NGINX_TAG}

docker push ${DOCKER_USERNAME}/${FPM_NAME}:${FPM_TAG}
docker push ${DOCKER_USERNAME}/${NGINX_NAME}:${NGINX_TAG}
