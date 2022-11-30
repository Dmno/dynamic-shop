# Dynamic shop

This project uses docker, which creates necessary containers for the project to run
Make sure docker is installed before attempting to launch it in a local environment

## Dependencies:

- php
- symfony

## Dev dependencies:

- docker

## Launching the project

1. Creating the container
    - ```docker-compose up -d --build``` (Launches and builds a docker container when launching for the first time)
    - ```docker-compose up -d``` (Regular launch for an already existing container)
2. ```composer install``` (Installs necessary packages and dependencies)

## Setting up the project

- ```docker-compose exec php php bin/console doctrine:migrations:migrate``` (Migrates already created migrations to setup a database structure)
- ```docker-compose exec php php bin/console doctrine:fixtures:load``` (Populates the database with dummy data so something is visible in the site)

## Previewing the project

- Navigate to the browser and access http://localhost:8001 to see the site


### TODOs:


