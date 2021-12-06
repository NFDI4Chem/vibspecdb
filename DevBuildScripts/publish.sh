#!/bin/sh
# this is for a local development, for a production use GitLab CI/CD pipelines with a dind-gitlab-runner;

cd ../
npm run prod
cd build

DOCKER_USERNAME=docker.photonicdata.science
NAME=vibspecdb_laravel_backend
TAG=v0.1.0
# docker image tag vibspecdb_laravel_backend:latest ${DOCKER_USERNAME}/${NAME}:${TAG}	
docker build --tag ${DOCKER_USERNAME}/${NAME}:${TAG} . &&  docker push ${DOCKER_USERNAME}/${NAME}:${TAG}
