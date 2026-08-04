compose_up_d_local:
	docker compose  -f docker-compose.local.yml up  --build -d

compose_up_local:
	docker compose  -f docker-compose.local.yml up  --build

compose_down_local:
	docker compose  -f docker-compose.local.yml down

compose_clean_local:
	docker compose  -f docker-compose.local.yml down
	docker compose  -f docker-compose.local.yml rm


compose_up_d:
	docker compose  -f docker-compose.prod.yml up  --build -d

compose_up:
	docker compose  -f docker-compose.prod.yml up  --build

compose_down:
	docker compose  -f docker-compose.prod.yml down

compose_clean:
	docker compose  -f docker-compose.prod.yml down
	docker compose  -f docker-compose.prod.yml rm
