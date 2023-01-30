up: docker-up
down: docker-down
restart: down up
install: api-composer-install

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

api-composer-install:
	docker-compose run --rm api-php-cli composer install

migrations-diff:
	docker-compose run --rm api-php-cli symfony console make:migration

migrate:
	docker-compose run --rm api-php-cli symfony console doctrine:migrations:migrate

fixtures:
	docker-compose run --rm api-php-cli symfony console doctrine:fixtures:load
