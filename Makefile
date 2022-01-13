ENV_DOCKER=.env.docker

include $(ENV_DOCKER)

install: volumes%create ssl%create
	composer install

volumes%create:
	@mkdir -p $(NGINX_LOG_DIR) $(SSL_CERTS_DIR)

ssl%create:
	@openssl req -x509 -nodes -days 3650 -newkey rsa:2048 -subj "/C=DE/ST=Mecklenburg-Western Pomerania/L=Mecklenburg/O=Telegram/CN=localhost" -keyout $(SSL_CERTS_DIR)/server.key -out $(SSL_CERTS_DIR)/server.pem

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose restart

stop:
	docker compose stop

start: install up

doctor:
	@echo -n "Composer: " && type composer >/dev/null 2>&1 && echo -e "\033[1;36m[OK]\033[0m" || (echo -e "\033[5;31m[Not found]\033[0m";)
	@echo -n "mkdir: " && type mkdir >/dev/null 2>&1 && echo -e "\033[1;36m[OK]\033[0m" || (echo -e "\033[5;31m[Not found]\033[0m";)
	@echo -n "openssl: " && type openssl >/dev/null 2>&1 && echo -e "\033[1;36m[OK]\033[0m" || (echo -e "\033[5;31m[Not found]\033[0m";)
	@echo -n "Docker: " && type docker >/dev/null 2>&1 && echo -e "\033[1;36m[OK]\033[0m" || (echo -e "\033[5;31m[Not found]\033[0m";)
	@echo -n "Docker compose: " && type docker compose >/dev/null 2>&1 && echo -e "\033[1;36m[OK]\033[0m" || (echo -e "\033[5;31m[Not found]\033[0m";)
	@echo -n "PHP: " && type php >/dev/null 2>&1 && echo -e "\033[1;36m[OK]\033[0m" || (echo -e "\033[5;31m[Not found]\033[0m";)
