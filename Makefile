DOCKER = docker-compose
COMPOSER  = $(DOCKER) exec app composer

build:
	@$(DOCKER) build --pull --no-cache $(c)

up:
	@$(DOCKER) up $(c)

down:
	@$(DOCKER) down $(c) --remove-orphans

up-d:
	@$(DOCKER) up --detach $(c)

start: build up-d

stop:
	@$(DOCKER) stop $(c)

temper-parking:
	@$(DOCKER) exec app bin/console temper:parking
