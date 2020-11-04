docker-restart:
	docker-compose down
	docker-compose up -d

php-cli:
	docker-compose exec app-php-cli bash

tests-unit:
	docker-compose exec app-php-cli php bin/phpunit
