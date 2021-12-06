#!/bin/sh
# docker-compose up -d --force-recreate --renew-anon-volumes

docker-compose -f ../../docker-compose-k8s.yml up -d --force-recreate --renew-anon-volumes