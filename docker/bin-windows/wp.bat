#!/usr/bin/env bash
cd docker
docker-compose run --rm wpcli wp --allow-root %*
cd ..
