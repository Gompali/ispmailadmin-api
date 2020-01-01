#!make
ENV_FILE_EXISTS=$(shell [ -f .env.local ] && echo 1 || echo 0)
ifeq ($(ENV_FILE_EXISTS), 0)
    $(info Creating default .env file from .env.dist)
    $(shell cp .env .env.local)
endif
include .env.local

OS=$(shell uname)

ifeq ($(OS),Darwin)
	export UID = 1000
	export GID = 1000
else
	export UID = $(shell id -u)
	export GID = $(shell id -g)
endif

ifeq ($(CI),true)
	BEHAT_FLAGS := -p ci
else
	ifeq ($(FORCE_LOCAL),true)
		BEHAT_FLAGS := -p local
	else
		BEHAT_FLAGS :=
	endif
endif

APP_IMAGE_NAME?=ispmailadmin-api
APP_IMAGE_TAG?=latest
APP_IMAGE_NAMESPACE?=${DOCKER_HUB_USER}
APP_IMAGE=${APP_IMAGE_NAMESPACE}/${APP_IMAGE_NAME}

DOCKER_COMPOSE_FILE?=./docker/dev/docker-compose.yml
DOCKER_COMPOSE_PROD_FILE?=./docker/prod/docker-compose.yml
DOCKER_COMPOSE=docker-compose --file ${DOCKER_COMPOSE_FILE} --project-name=ispmailadmin-api
DOCKER_COMPOSE_PROD=docker-compose --file ${DOCKER_COMPOSE_PROD_FILE} --project-name=co-monolith
COMPOSER_AUTH?=$(shell test -f ~/.composer/auth.json && cat ~/.composer/auth.json)
DOCKER_FILE?=./docker/dev/Dockerfile

DOCKER_APP_BUILD_FILE?=./docker/prod/fpm/Dockerfile
DOCKER_APP_BUILD_DIR?=.
DOCKER_WEB_BUILD_FILE?=./docker/prod/nginx/Dockerfile
DOCKER_WEB_BUILD_DIR?=./public
DOCKER_WEB_CONF_DIR?=./docker/prod/nginx/conf.d


# Eecute command in container
RUN_IN_CONTAINER := docker exec -it ${APP_IMAGE_NAME}
RUN_IN_NODE := docker exec -it ${APP_IMAGE_NAME}_node
SUBCOMMAND = $(subst +,-, $(filter-out $@,$(MAKECMDGOALS)))

# Default Make
.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | cut -d: -f2- | sort -t: -k 2,2 | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

# Dependencies
.PHONY: start-deps
start-deps: ## (Docker) Start dependencies (for this project only)
	- docker-machine start
	@if [ -z ${DOCKER_DEPENDENCIES} ]; then \
		echo 'No dependencies in .env file !!'; \
		exit 1; \
	fi
	docker-env up ${DOCKER_DEPENDENCIES}

.PHONY: stop-deps
stop-deps: ## (Docker) Stop dependencies (for this project only)
	@docker-env stop ${DOCKER_DEPENDENCIES}

# Project containers
start-docker: ## (Docker) Start containers (for this project only)
	@${DOCKER_COMPOSE} up -d

stop-docker: ## (Docker) Stop containers (for this project only)
	$(RUN_IN_CONTAINER) symfony server:stop
	@${DOCKER_COMPOSE} down --remove-orphans

# All
.PHONY: start
start: start-deps start-docker ## (Docker) Start : dependencies and containers (for this project only)

.PHONY: stop
stop:  stop-deps stop-docker ## (Docker) Stop : dependencies and containers (for this project only)

.PHONY: restart
restart: stop start ## (Docker) Restart containers and deps (for this project only)

.PHONY: recreate
recreate: stop ## (Docker) Stop : dependencies and containers with re-build app container (for this project only)
	@if [ "$(APP_ENV)" = "dev" ]; then \
		${DOCKER_COMPOSE} up --force-recreate --build -d; \
	else \
		${DOCKER_COMPOSE} up --force-recreate -d; \
	fi

.PHONY: ps
ps: ## (Docker) Show containers (for this project only)
	@echo "\nProject :\n"
	@${DOCKER_COMPOSE} ps
	@echo "\n\n\nOthers :\n"
	@docker ps -s | grep 'dockerenv'

.PHONY: unit
unit: ## (PHP) Unit tests
	$(RUN_IN_CONTAINER) ./vendor/bin/phpunit --bootstrap vendor/autoload.php

.PHONY: behat
behat: vendor ## (PHP) Behavior tests
	$(RUN_IN_CONTAINER) $(MAKE) $@ $(BEHAT_FLAGS)

.PHONY: build
build: ## (Docker) Start dependencies (for this project only)
	@rm -rf public/build/*
	make assets
	docker build -t "${APP_IMAGE}-app:${APP_IMAGE_TAG}" --file "${DOCKER_APP_BUILD_FILE}" --build-arg src=${DOCKER_APP_BUILD_DIR} .
	docker build -t "${APP_IMAGE}-web:${APP_IMAGE_TAG}" --file "${DOCKER_WEB_BUILD_FILE}" --build-arg src=${DOCKER_WEB_BUILD_DIR} --build-arg conf="${DOCKER_WEB_CONF_DIR}" .

.PHONY: push
push: ## (Docker) Push image to dkcer hub
	@docker logout
	@echo  "${DOCKER_PASSWORD}" | docker login --username ${DOCKER_HUB_USER} --password-stdin
	docker push ${APP_IMAGE}-app:${APP_IMAGE_TAG}
	docker push ${APP_IMAGE}-web:${APP_IMAGE_TAG}

.PHONY: docker-tag
docker-tag: ## (Docker) Tag image
	@docker tag ${APP_IMAGE_NAMESPACE}/${APP_IMAGE_NAME}

.PHONY: docker-login
docker-login: # (Docker) Login
	docker login -u ${DOCKER_USER} -p ${DOCKER_PASSWORD}

.PHONY: update
update: ## (Docker) Update image docker
	docker-compose pull
	docker-compose down
	docker-compose up -d
	docker exec -ti ${APP_IMAGE_NAME} bash -c \
	"composer install --optimize-autoloader --ansi --no-dev --no-progress --no-suggest --classmap-authoritative --no-interaction \
	&& bin/console cache:clear \
	&& chmod 777 -R var/cache \
	&& chmod 777 -R var/log

.PHONY: generate-jwt
generate-jwt: ## (OpenSSL) Generate certs for JWT
	@${RUN_IN_CONTAINER} sh -c "mkdir -p ./var/jwt"
	@${RUN_IN_CONTAINER} sh -c "openssl genrsa -aes256 -passout pass:${PROJECT_NAME} -out ./var/jwt/private.pem 4096"
	@${RUN_IN_CONTAINER} sh -c "openssl rsa -in ./var/jwt/private.pem -passin pass:${PROJECT_NAME} -pubout > ./var/jwt/public.pem"
	@${RUN_IN_CONTAINER} sh -c "openssl rsa -in ./var/jwt/private.pem -passin pass:${PROJECT_NAME}"
	@${RUN_IN_CONTAINER} sh -c "ls -lah ./var/jwt/"


.PHONY: start-docker-sync
start-docker-sync: ## (Docker) Start docker-sync
	docker-sync start --app-name=score-api --config=docker-sync.yml

.PHONY: stop-docker-sync
stop-docker-sync: ## (Docker) Stop docker-sync (with this project only)
	docker-sync stop --app-name=score-api --config=docker-sync.yml
	docker-sync clean --config=docker-sync.yml

.PHONY: shell
shell: ## (Docker) Enter in app container
	$(RUN_IN_CONTAINER) sh

.PHONY: node
node:
	@docker exec -ti ${APP_IMAGE_NAME}_node sh

.PHONY: yarn
yarn: ## (FRONT) yarn
	@${RUN_IN_NODE} sh -c "yarn	&& yarn install"

.PHONY: watch
watch: ## (FRONT) watch : encore watch ~ yarn run encore dev --watch
	@${RUN_IN_NODE} sh -c "yarn run encore dev --watch"

.PHONY: assets
assets: ## (FRONT) build assets
	${RUN_IN_NODE} yarn encore production;

