APP=laravel_app
DC=docker-compose

# ========================
# 🚀 AMBIENTE
# ========================

up:
	$(DC) up -d --build

down:
	$(DC) down

restart:
	$(DC) down && $(DC) up -d --build

logs:
	$(DC) logs -f

# ========================
# ⚡ SETUP INICIAL (MENTORIA)
# ========================

setup:
	$(DC) up -d --build
	sleep 5
	docker exec -it $(APP) composer install
	docker exec -it $(APP) cp .env.example .env || true
	docker exec -it $(APP) php artisan key:generate
	docker exec -it $(APP) php artisan migrate:fresh --seed

first-run: setup

# ========================
# 🐘 LARAVEL
# ========================

bash:
	docker exec -it $(APP) bash

artisan:
	docker exec -it $(APP) php artisan

migrate:
	docker exec -it $(APP) php artisan migrate

fresh:
	docker exec -it $(APP) php artisan migrate:fresh --seed

seed:
	docker exec -it $(APP) php artisan db:seed

tinker:
	docker exec -it $(APP) php artisan tinker

# ========================
# 📦 COMPOSER
# ========================

composer:
	docker exec -it $(APP) composer

install:
	docker exec -it $(APP) composer install

update:
	docker exec -it $(APP) composer update

dump:
	docker exec -it $(APP) composer dump-autoload

# ========================
# 🧪 TESTES
# ========================

test:
	docker exec -it $(APP) php artisan test

phpunit:
	docker exec -it $(APP) vendor/bin/phpunit

coverage:
	docker exec -it $(APP) vendor/bin/phpunit --coverage-text

# ========================
# 🧠 QUALIDADE (BASE CI)
# ========================

ci:
	docker exec -it $(APP) php artisan test
	docker exec -it $(APP) vendor/bin/phpunit

# ========================
# 🧹 LIMPEZA
# ========================

clear:
	docker exec -it $(APP) php artisan optimize:clear

cache:
	docker exec -it $(APP) php artisan optimize

# ========================
# 🔐 PERMISSÕES
# ========================

perm:
	sudo chown -R $$USER:$$USER .
	docker exec -it $(APP) chown -R www-data:www-data storage bootstrap/cache || true
	docker exec -it $(APP) chmod -R 775 storage bootstrap/cache || true

# ========================
# 💣 RESET TOTAL
# ========================

reset:
	$(DC) down -v
	$(DC) up -d --build