SHELL := /bin/bash

.DEFAULT_GOAL := help

DC := docker compose

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## ' $(MAKEFILE_LIST) | awk 'BEGIN {FS":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

up: ## Start the stack
	$(DC) up -d --build

logs: ## Tail logs
	$(DC) logs -f

down: ## Stop the stack
	$(DC) down

clean: ## Stop and remove volumes
	$(DC) down -v

cli: ## Open WP-CLI shell
	$(DC) run --rm cli bash

install: ## Install WordPress core and set site up
	$(DC) run --rm cli wp core download --path=/var/www/html --force
	$(DC) run --rm cli wp config create --path=/var/www/html --dbname=wordpress --dbuser=wordpress --dbpass=wordpress --dbhost=db --skip-check
	$(DC) run --rm cli wp core install --path=/var/www/html --url=http://localhost:8080 --title="Blearti21" --admin_user=admin --admin_password=admin --admin_email=admin@example.com
	$(DC) run --rm cli wp theme activate blearti21
	$(DC) run --rm cli wp plugin activate blearti-core
