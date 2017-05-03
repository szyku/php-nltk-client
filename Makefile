ROOT := $(shell pwd)
APP_DIR = $(ROOT)
COMPOSER_IMG = composer/composer:php5-alpine
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

composer_update:
	@docker run --rm -v $(APP_DIR):/app $(COMPOSER_IMG) update $(ARGS)

composer_require:
	@docker run --rm -v $(APP_DIR):/app $(COMPOSER_IMG) require $(ARGS)

composer_remove:
	@docker run --rm -v $(APP_DIR):/app $(COMPOSER_IMG) remove $(ARGS)

unit:
	@docker run -it --rm -v $(APP_DIR):/usr/src/lib -w /usr/src/lib php:5.6-cli bin/phpunit
tests: unit

