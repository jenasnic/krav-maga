SHELL = /bin/sh
USER_ID=$(shell id -u)
USER_GROUP=$(shell id -g)
DOCKER_ROOT=docker-compose run --rm
DOCKER_USER=docker-compose run --rm -u $(USER_ID):$(USER_GROUP)

SYMFONY_BIN=php ./bin/console
COMPOSER_BIN=php composer
PHPSTAN_BIN=php php -d memory_limit=-1 /usr/local/bin/phpstan
PHP_CS_FIXER_BIN=php /usr/local/bin/php-cs-fixer
YARN_BIN=node yarn

ifndef APP_ENV
	export APP_ENV:=dev
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
	$(DOCKER_ROOT) $(SYMFONY_BIN) cache:clear
	$(DOCKER_ROOT) rm -f ./var/log/$(APP_ENV).log



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
	$(DOCKER_USER) $(YARN_BIN) install
	$(DOCKER_USER) $(YARN_BIN) build



##
## For deployment
##---------------------------------------------------------------------------

.PHONY: prod
prod:
	$(DOCKER_USER) $(COMPOSER_BIN) install --no-dev --optimize-autoloader --no-interaction
	$(DOCKER_USER) $(YARN_BIN) install
	$(DOCKER_USER) $(YARN_BIN) build --prod



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
	$(DOCKER_USER) $(PHP_CS_FIXER_BIN) --dry-run --diff-format=udiff fix --format=txt --verbose --show-progress=estimating

.PHONY: phpcsfix
phpcsfix:
	$(DOCKER_USER) $(PHP_CS_FIXER_BIN) fix --format=txt --verbose --show-progress=estimating

.PHONY: phpstan
phpstan:
	$(DOCKER_USER) $(PHPSTAN_BIN) analyse --level=6 src
