.PHONY: up down migrate seed test

up:
	php artisan serve

down:
	@pkill -f "artisan serve" || true

migrate:
	php artisan migrate

seed:
	php artisan db:seed

test:
	php artisan test
