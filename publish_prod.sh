#!/bin/sh

DOCKER_USERNAME=docker.photonicdata.science

FPM_NAME=vibspecdb_laravel_fpm
FPM_TAG=latest # $(sed -n 2p version.txt)

NGINX_NAME=vibspecdb_nginx
NGINX_TAG=latest # $(sed -n 4p version.txt)

docker build . -f ./resources/ops/app/Dockerfile --tag ${DOCKER_USERNAME}/${FPM_NAME}:${FPM_TAG} 
docker build . -f ./resources/ops/nginx/Dockerfile --tag ${DOCKER_USERNAME}/${NGINX_NAME}:${NGINX_TAG} 

docker push ${DOCKER_USERNAME}/${FPM_NAME}:${FPM_TAG}
docker push ${DOCKER_USERNAME}/${NGINX_NAME}:${NGINX_TAG}
