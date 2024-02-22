#!/usr/bin/env bash

cd "$(dirname "$0")"

# Stop the Container
docker stop test

# Removing the Container
docker rm test

# Removing the Image
docker rmi test

# Clearing all Caches
docker system prune -a

# Creating a new Image from Dockerfile
docker image build -t test .

# Launching the new Container based on the Image
docker run -d -p 8080:80 --name test test
