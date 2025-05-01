DOCKER=docker
AWS=aws

localstack-up:
	$(DOCKER) run -d --rm -e SERVICES=s3,ses,sts -p 4566:4566 localstack/localstack

s3-create:
	$(AWS) --endpoint-url=http://localhost:4566 s3 mb s3://test-bucket

ses-verify-email:
	$(AWS) --endpoint-url=http://localhost:4566 ses verify-email-identity --email-address "belford2014@gmail.com"

magic: localstack-up s3-create ses-verify-email

docker-down:
	$(DOCKER) stop $$(docker ps -q)

down: docker-down

phpstan:
	./vendor/bin/phpstan analyse --level=8 --no-progress --memory-limit=2G

php-cs-fixer:
	./vendor/bin/php-cs-fixer fix --dry-run --diff


php-cs-fixer-fix:
	./vendor/bin/php-cs-fixer fix

phpstan-and-fix:
	./vendor/bin/phpstan analyse --level=8 --no-progress --memory-limit=2G && ./vendor/bin/php-cs-fixer fix
