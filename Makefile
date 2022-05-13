cs-check:
	./vendor/bin/ecs check src tests tests-api

cs-fix:
	./vendor/bin/ecs check src tests tests-api --fix
