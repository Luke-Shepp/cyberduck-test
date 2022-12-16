# CD Local Environment Setup Scripts
# Original idea from: https://gist.github.com/mpneuried/0594963ad38e68917ef189b4e6a269db
# DOCKER TASKS
start: ## Start the pre-built environment.
	docker-compose up -d --build

stop: ## Stops/pause the environment.
	docker-compose stop

install: ## Builds the environment for the first and starts it
	cp .env.example ./.env
	docker-compose up -d --build
	docker-compose exec php-fpm composer install
	docker-compose exec php-fpm php artisan key:generate
	docker-compose exec php-fpm php artisan storage:link
	docker-compose exec php-fpm php artisan migrate:fresh --seed
	npm install
	npm run dev
	@echo -n "Happy coding! http://coffee-shop.localhost"

down: ## Remove containers
	docker-compose down

destroy: ## Remove containers and volumes
	docker-compose down --volumes --remove-orphans

destroy-images: ## Remove containers, volumes and images
	docker-compose down --rmi all --volumes --remove-orphans

cs-fix: ## Formats the code following the Project Standards
	vendor/bin/php-cs-fixer fix --config=.php_cs.laravel.php

# HELP
# This will output the help for each task
# thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
.PHONY: help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
