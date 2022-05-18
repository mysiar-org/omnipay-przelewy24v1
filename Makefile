cs-check:
	./vendor/bin/ecs check src tests tests-api

cs-fix:
	./vendor/bin/ecs check src tests tests-api --fix

changelog:
	npx auto-changelog -o CHANGELOG.md

test:
	./vendor/bin/phpunit --no-coverage

coverage:
	php -d xdebug.mode=coverage ./vendor/bin/phpunit
