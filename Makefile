cs-check:
	./vendor/bin/ecs check src tests tests-api

cs-fix:
	./vendor/bin/ecs check src tests tests-api --fix

changelog:
	npx auto-changelog -o CHANGELOG.md

coverage:
	php -d xdebug.mode=coverage ./vendor/bin/phpunit
