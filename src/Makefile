# Variables
LARAVEL_CONTAINER=laravel.test
REDIS_CONTAINER=redis
PGSQL_CONTAINER=pgsql

# Commands
.PHONY: help
help: ## Shows this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

### Commands related to containers Start ###
up: ## Start the containers
	./vendor/bin/sail up -d

down: ## Stop the containers
	./vendor/bin/sail down

login-laravel: ## Login to laravel container
	./vendor/bin/sail exec -it $(LARAVEL_CONTAINER) bash

login-redis: ## Login to redis container
	./vendor/bin/sail exec -it $(REDIS_CONTAINER) sh

login-pgsql: ## Login to pgsql container
	./vendor/bin/sail exec -it $(PGSQL_CONTAINER) psql -U postgres
### Commands related to containers End ###

### Common Laravel actions Start ###
clear-cache: ## Clear cache
	./vendor/bin/sail exec -it $(LARAVEL_CONTAINER) php artisan cache:clear
	./vendor/bin/sail exec -it $(LARAVEL_CONTAINER) php artisan config:clear
	./vendor/bin/sail exec -it $(LARAVEL_CONTAINER) php artisan route:clear
	./vendor/bin/sail exec -it $(LARAVEL_CONTAINER) php artisan view:clear

migrate: ## Run migrations
	./vendor/bin/sail exec -it $(LARAVEL_CONTAINER) php artisan migrate --force

migrate-fresh: ## Run fresh migrations
	./vendor/bin/sail exec -it $(LARAVEL_CONTAINER) php artisan migrate:fresh

composer-install: ## Install composer dependencies
	./vendor/bin/sail exec -it $(LARAVEL_CONTAINER) composer install
### Common Laravel actions End ###

### Code quality Start ###
phpcs: ## Run PHP Code Sniffer
	./vendor/bin/sail exec -it $(LARAVEL_CONTAINER) ./vendor/bin/phpcs app --standard=PSR12

phpcbf: ## Run PHP Code Beautifier and Fixer
	./vendor/bin/sail exec -it $(LARAVEL_CONTAINER) ./vendor/bin/phpcbf app --standard=PSR12

phpstan: ## Run PHPStan
	./vendor/bin/sail exec -it $(LARAVEL_CONTAINER) ./vendor/bin/phpstan analyse

rector: ## Run Rector
	./vendor/bin/sail exec -it $(LARAVEL_CONTAINER) ./vendor/bin/rector process

rector-dry: ## Run Rector in dry mode
	./vendor/bin/sail exec -it $(LARAVEL_CONTAINER) ./vendor/bin/rector process --dry-run

code: ## Run code checks only
	make phpcs
	make phpstan
	make rector-dry

fix: ## Fix code issues
	make phpcbf
	make rector

test: ## Run tests
	./vendor/bin/sail exec -it $(LARAVEL_CONTAINER) ./vendor/bin/phpunit

### Code quality End ###
