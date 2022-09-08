# The targets in this file are executed by your ci/cd pipelines. Make sure you configure them for your needs!

DOCKER_REPOSITORY="registry.lf.net/ext/ede/ede-search/search-service"
APP_CONTAINER_NAME="search-dummy"
PHIVE_KEYS="4AA394086372C20A,CF1A108D0E7AE720,E82B2FB314E9906E"

# run on host
local:
	@docker compose up -d --force-recreate app
	@docker compose up -d
	@docker exec $(APP_CONTAINER_NAME) make setup
	@docker exec $(APP_CONTAINER_NAME) make var/key
	@if [ -z "${NO_ATTACH}" ]; then make attach; fi
prod:
	@make IMAGE=prod production-image
	@docker compose up -d --force-recreate app-prod
stage:
	@make IMAGE=stage production-image
	@docker compose up -d --force-recreate app-stage
swoole:
	@rm ./docker/app/Dockerfile || true
	@docker run --rm -i -v $$(pwd)/docker/app/build:/root:ro coryb/dfpp swoole.Dockerfile > ./docker/app/Dockerfile
	@docker buildx build --push --platform linux/arm/v7,linux/arm64/v8,linux/amd64 -t $(DOCKER_REPOSITORY):swoole ./docker/app
	@rm ./docker/app/Dockerfile || true
release: production-image
	docker push $(DOCKER_REPOSITORY):$(IMAGE)
production-image:
	rm ./docker/app/Dockerfile || true
	docker run --rm -i -v $$(pwd)/docker/app/build:/root:ro coryb/dfpp $(IMAGE).Dockerfile > ./docker/app/Dockerfile
	docker build --build-arg PHIVE_KEYS=$(PHIVE_KEYS) --build-arg GITHUB_AUTH_TOKEN=$(GITHUB_AUTH_TOKEN) -t $(DOCKER_REPOSITORY):$(IMAGE) -f ./docker/app/Dockerfile .
	docker image rm -f $(APP_CONTAINER_NAME)-swoole:latest
#	reactivate when in CI to make sure production images are save to use
#	@docker scan --accept-license $(DOCKER_REPOSITORY):$(IMAGE) -f ./docker/app/Dockerfile --severity medium
	rm ./docker/app/Dockerfile

image:
	@rm ./docker/app/Dockerfile || true
	@docker run --rm -i -v $$(pwd)/docker/app/build:/root:ro coryb/dfpp $(IMAGE).Dockerfile > ./docker/app/Dockerfile
	@docker buildx build --push --platform linux/arm/v7,linux/arm64/v8,linux/amd64 -t $(DOCKER_REPOSITORY):$(IMAGE) ./docker/app
	@rm ./docker/app/Dockerfile || true

tunnel:
	@docker compose stop elasticsearch
	@ssh -L 9200:test-es6:9200 -p 8023 root@dck01.elc.ede.bawue.com
attach:
	@docker exec -it $(APP_CONTAINER_NAME) sh
fix:
	@docker exec -it $(APP_CONTAINER_NAME) php-cs-fixer fix --config=.php-cs-fixer.php -v --allow-risky yes

# run on container
tests: cpd-test yaml-lint php-cs-lint phpstan unit-tests
cpd-test:
	@php ./bin/phpcpd ./src --min-lines 5
yaml-lint:
	@php ./bin/console lint:yaml --parse-tags ./config
php-cs-lint:
	@php ./bin/php-cs-fixer fix --config=.php-cs-fixer.php -v --dry-run --stop-on-violation --using-cache=no --allow-risky yes
phpstan:
	@php ./bin/phpstan analyse
unit-tests:
	@php ./bin/phpunit --no-coverage --order-by random
update-phive:
	@phive install --trust-gpg-keys $(PHIVE_KEYS)
phive:
	phive install --trust-gpg-keys $(PHIVE_KEYS)
setup: phive
	composer install
var/key:
	@openssl genrsa -out ./var/key 2048
