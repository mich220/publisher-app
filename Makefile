## Setup
DOCKER 					= docker-compose
APP_IMAGE_REGISTRY 		= localhost:5000/publisher:latest
APP_BUILD_IMAGE 		= php:7.4.0-fpm
APP_DOCKERFILE			= .docker/default/php/Dockerfile
UID     			= $(shell id -u)

## App makefile
build:
	@docker build -t $(APP_IMAGE_REGISTRY) --no-cache --build-arg APP_IMAGE=$(APP_BUILD_IMAGE) --build-arg DEV_UID=$(UID) -f $(APP_DOCKERFILE) .

up:
	@cd ./.docker && $(DOCKER) up -d

down:
	@cd ./.docker && $(DOCKER) down --remove-orphans

console:
	@docker exec -it publisher-app bash