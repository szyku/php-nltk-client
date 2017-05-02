ROOT := $(shell pwd)
APP_DIR = $(ROOT)
DEF_SHELL =sh -c
COMPOSER_IMG = composer/composer:php5-alpine
APP_CONTAINER = tc_php-fpm
ARGS ?=

IS_DOCKER_AVAILABLE := $(shell command -v docker 2> /dev/null)
ifndef IS_DOCKER_AVAILABLE
$(error "This Makefile uses docker to build the app. Seems docker is not installed.")
endif

.PHONY: composer composer_update composer_require composer_remove all

# How to use ARGS
# make unit ARGS='--group integration'

build: composer

composer:
	@docker run --rm -v $(APP_DIR):/app $(COMPOSER_IMG) install --no-interaction
unit:
	@docker run -it --rm -v $(APP_DIR):/usr/src/lib -w /usr/src/lib php:5.6-cli bin/phpunit
tests: unit

