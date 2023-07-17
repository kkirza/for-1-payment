ENV_FILE = .env
DOCKER_COMPOSE = docker compose -f docker-compose.yml --env-file ${ENV_FILE}
DOCKER = docker
COMPOSER = composer
USER_ID = $(shell id -u)

.PHONY: *
SHELL=/bin/bash -o pipefail

help: ## Показывает справку по Makefile
	@printf "\033[33m%s:\033[0m\n" 'Доступные команды'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

init: ## Сборка и запуск проекта
	-${MAKE} stop start install migration

writeCsv:
	$(DOCKER_COMPOSE) exec -u $(USER_ID) -T php-fpm php bin/console writeCsv

install: ## Установка зависимостей
	$(DOCKER_COMPOSE) exec -u $(USER_ID) -T php-fpm $(COMPOSER) i -a -o

clean: ## Остановка и очистка контейнеров
	$(DOCKER_COMPOSE) down --rmi local -v
start: ## Запуск контейнеров
	$(DOCKER_COMPOSE) up -d
restart: stop start
stop: ## Остановка контейнеров
	$(DOCKER_COMPOSE) down

shell-php: ## Вход в контейенер приложения
	$(DOCKER_COMPOSE) exec -u $(USER_ID) php-fpm bash
shell-nginx: ## Вход в контейнре nginx-а
	$(DOCKER_COMPOSE) exec nginx sh
shell-db: ## Вход в контейнер с базой данных
	$(DOCKER_COMPOSE) exec mysql bash

logs-php: ## Получить логи приложения
	$(DOCKER_COMPOSE) logs php-fpm
logs-nginx: ## Получить nginx логи
	$(DOCKER_COMPOSE) logs nginx
logs-db: ## Получить бд логи
	$(DOCKER_COMPOSE) logs mysql

ps: ## Показать контейнеры проекта
	$(DOCKER_COMPOSE) ps

migration: ## выполняет миграции
	$(DOCKER_COMPOSE) exec -u $(USER_ID) -T php-fpm php bin/console d:m:m -n
