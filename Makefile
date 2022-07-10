start:
	docker compose up -d

build:
	docker compose -f "compose.yml" up -d --build

stop:
	docker compose stop

remove:
	docker compose stop
	docker compose rm

bash:
	docker exec -it --user 1000 php-apache bash

mysql/bash:
	docker exec -it mysql bash
