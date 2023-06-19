SHELL := /usr/bin/env bash
#COMPOSER_HOME := $$HOME/.config/composer
#COMPOSER_CACHE_DIR := $$HOME/.cache/composer

up: check_uid_and_env_vars
	export COMPOSER_HOME=$(COMPOSER_HOME) && export COMPOSER_CACHE_DIR=$(COMPOSER_CACHE_DIR) && export UID && docker-compose up -d
	bin/docker-compose-exit-check.sh
	bin/wait_for_docker.bash "autoload files"
	bin/wait_for_docker.bash "mysqld: ready for connections."

up_ci: check_uid_and_env_vars
	export COMPOSER_HOME=$(COMPOSER_HOME) && export COMPOSER_CACHE_DIR=$(COMPOSER_CACHE_DIR) && export UID && docker-compose -f docker-compose.ci.yml up -d
	bin/docker-compose-exit-check.sh
	bin/wait_for_docker.bash "autoload files"
	bin/wait_for_docker.bash "mysqld: ready for connections."

logs_tail:
	if [ -z "$(UID)" ]; then echo "UID variable required, please run 'export UID' before running make task"; exit 1 ; fi
	export UID && docker-compose logs -f

down:
	docker-compose down -v

build: check_uid_and_env_vars
	docker-compose build --no-cache

bash: check_uid_and_env_vars
	docker-compose exec php bash

bash_mysql: check_uid_and_env_vars
	export UID && docker-compose run db bash

composer_bash: check_uid_and_env_vars
	docker-compose run --rm composer bash

tests: check_uid_and_env_vars
	docker-compose run --rm php bash -c "vendor/bin/phpunit --coverage-html=reports/coverage-html"
.PHONY: tests

thirdparty_tests: _behat_symfony_files
	docker-compose run --rm php bash -c "vendor/bin/phpunit --group thirdPartyIntegrations"

integration_tests: _behat_symfony_files
	docker-compose run --rm php bash -c "vendor/bin/behat"

phpcs:
	docker-compose run --rm php bash -c "vendor/bin/phpcs ."

phpcbf:
	docker-compose run --rm php bash -c "vendor/bin/phpcbf ."

phpmd:
	docker-compose run --rm php bash -c "vendor/bin/phpmd  src/ html phpmd.xml --exclude Client/EtsyOpenAPI* --ignore-violations-on-exit --reportfile reports/phpmd.report.html"

phpmetrics:
	docker-compose run --rm php bash -c "vendor/bin/phpmetrics --report-html=reports/phpmetrics-html src --exclude=EtsyOpenAPI"

# phpcpd: PACKAGE abandoned, need to look for alternative
# 	docker-compose run --rm php bash -c "vendor/bin/phpcpd --exclude=src/Client/OpenAPI src/"

securitychecker:
	docker-compose run --rm php bash -c "symfony local:check:security"

exec:
	export COMPOSER_HOME=$(COMPOSER_HOME) && export COMPOSER_CACHE_DIR=$(COMPOSER_CACHE_DIR) && export UID && docker-compose run composer git config --global --list

php-cs-fixer:
	docker-compose run --rm php bash -c "vendor/bin/php-cs-fixer fix src"

docker_clean:
	docker rm $(docker ps -a -q) || true
	docker rmi < echo $(docker images -q | tr "\n" " ")

clean: down
	rm -rf vendor

check_uid_and_env_vars:
	if [ -z "$(UID)" ]; then echo "UID variable required, please run 'export UID' before running make task"; exit 1 ; fi
