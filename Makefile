.PHONY: up
up:
	docker-compose build
	docker-compose up -d
	docker exec -it unit-testing-api-app ./bin/setup-docker.sh

.PHONY: bash
bash:
	docker exec -it unit-testing-api-app /bin/bash

.PHONY: down
down:
	docker-compose down

.PHONY: clean
clean:
	docker-compose down -v

.PHONY: tests
tests:
	docker exec unit-testing-api-app ./bin/console cache:clear --env=tests
	docker exec unit-testing-api-app ./vendor/bin/phpunit tests

.PHONY: testunit
testunit:
	docker exec unit-testing-api-app ./vendor/bin/phpunit tests/Application

.PHONY: composer-update
composer-update:
	docker exec unit-testing-api-app composer update
