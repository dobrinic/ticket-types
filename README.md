### Running project

If your system has GNU make installed you can use Makefile with command `make init` to build the project and use other shorthand methods.

If you don't have GNU make installed please run:
```shell
	docker compose -p dhimahi-aleks up -d
	docker compose -p dhimahi-aleks exec server composer install
```
For testing run `make test`
or
```shell
	docker compose -p dhimahi-aleks exec server php ./vendor/bin/phpunit
```