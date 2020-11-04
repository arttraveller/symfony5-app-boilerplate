docker-restart:
	docker-compose down
	docker-compose up -d

make-migration:
	docker-compose exec app-php-cli php bin/console make:migration

migrate:
	docker-compose exec app-php-cli php bin/console doctrine:migrations:migrate --no-interaction

php-cli:
	docker-compose exec app-php-cli bash

tests-unit:
	docker-compose exec app-php-cli php bin/phpunit
