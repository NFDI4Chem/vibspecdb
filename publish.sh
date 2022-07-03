#!/bin/sh

DOCKER_USERNAME=docker.photonicdata.science

FPM_NAME=serviceapp_laravel_fpm
FPM_TAG=$(cat ./version.txt)

NGINX_NAME=serviceapp_nginx
NGINX_TAG=v0.1.0

docker-compose build

docker image tag serviceapp_php:latest ${DOCKER_USERNAME}/${FPM_NAME}:${FPM_TAG}
docker image tag serviceapp_nginx:latest ${DOCKER_USERNAME}/${NGINX_NAME}:${NGINX_TAG}

docker push ${DOCKER_USERNAME}/${FPM_NAME}:${FPM_TAG}
docker push ${DOCKER_USERNAME}/${NGINX_NAME}:${NGINX_TAG}
