default: up

init:
	docker compose -p dhimahi-aleks up -d
	docker compose -p dhimahi-aleks exec server composer install
	@echo Wisit app on http://localhost:8080

up:
	docker compose -p dhimahi-aleks up -d
	@echo Wisit app on http://localhost:8080

down:
	docker compose -p dhimahi-aleks down

composer:
	docker compose -p dhimahi-aleks exec server composer install

test:
	docker compose -p dhimahi-aleks exec server php ./vendor/bin/phpunit

bash:
	@docker compose -p dhimahi-aleks exec -it server bash