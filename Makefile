down: docker-down-clear
up: docker-up
exec: docker-exec

docker-up:
	docker-compose up -d

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-exec:
	docker-compose exec php-fpm sh

