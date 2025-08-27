SHELL = /bin/sh
USER_ID=$(shell id -u)
USER_GROUP=$(shell id -g)
DOCKER_ROOT=docker-compose run --rm
DOCKER_USER=docker-compose run --rm -u $(USER_ID):$(USER_GROUP)
PHP_CS_FIXER_CONFIGURATION_FILE=.php-cs-fixer.php

SYMFONY_BIN=php ./bin/console
COMPOSER_BIN=php composer
NPM_BIN=node npm

PHP_QA=docker run --init -it --rm -v `pwd`:/project --workdir="/project" jakzal/phpqa:latest
PHPSTAN_BIN=php php -d memory_limit=-1 vendor/bin/phpstan

ifndef APP_ENV
	export APP_ENV:=prod
endif



##
## Commands
##---------------------------------------------------------------------------

.PHONY: install
install: vendor database assets

.PHONY: vendor
vendor:
	$(DOCKER_USER) $(COMPOSER_BIN) install --prefer-dist --no-interaction

.PHONY: cache
cache:
	$(DOCKER_USER) $(SYMFONY_BIN) cache:clear
	$(DOCKER_USER) rm -f ./var/log/$(APP_ENV).log



##
## Database
##---------------------------------------------------------------------------

.PHONY: database
database: dropdb createdb fixtures

.PHONY: dropdb
dropdb:
	$(DOCKER_USER) $(SYMFONY_BIN) doctrine:database:drop --force --if-exists

.PHONY: createdb
createdb:
	$(DOCKER_USER) $(SYMFONY_BIN) doctrine:database:create --if-not-exists
	$(DOCKER_USER) $(SYMFONY_BIN) doctrine:schema:update --force

.PHONY: fixtures
fixtures:
	$(DOCKER_USER) $(SYMFONY_BIN) doctrine:fixtures:load --no-interaction



##
## Assets
##---------------------------------------------------------------------------

.PHONY: assets
assets:
	$(DOCKER_USER) $(NPM_BIN) install
	$(DOCKER_USER) $(NPM_BIN) run build



##
## For deployment
##---------------------------------------------------------------------------

.PHONY: prod
prod:
	rm -rf public/build
	$(DOCKER_USER) $(NPM_BIN) run build --prod
	tar -cf build.tar public/build

.PHONY: deploy
deploy:
	$(DOCKER_USER) $(COMPOSER_BIN) install --no-dev --optimize-autoloader --no-interaction
	$(DOCKER_USER) $(NPM_BIN) install
	$(DOCKER_USER) $(NPM_BIN) run build --prod



##
## Check
##---------------------------------------------------------------------------

.PHONY: check
check: vcomposer vschema lyaml ltwig phpcs phpstan

.PHONY: vcomposer
vcomposer:
	$(DOCKER_USER) $(COMPOSER_BIN) validate

.PHONY: vschema
vschema:
	$(DOCKER_USER) $(SYMFONY_BIN) doctrine:schema:validate --skip-sync

.PHONY: lyaml
lyaml:
	$(DOCKER_USER) $(SYMFONY_BIN) lint:yaml --parse-tags config/

.PHONY: ltwig
ltwig:
	$(DOCKER_USER) $(SYMFONY_BIN) lint:twig templates/

.PHONY: phpcs
phpcs:
	$(PHP_QA) php-cs-fixer fix --dry-run --format=txt --verbose --show-progress=dots --config=$(PHP_CS_FIXER_CONFIGURATION_FILE)

.PHONY: phpcsfix
phpcsfix:
	$(PHP_QA) php-cs-fixer fix --format=txt --verbose --show-progress=dots --config=$(PHP_CS_FIXER_CONFIGURATION_FILE)

.PHONY: phpstan
phpstan:
	$(DOCKER_USER) $(PHPSTAN_BIN) analyse src --configuration='./phpstan.neon'
