# Darna : commandes de développement
# `make` ou `make help` pour afficher la liste.

CONSOLE = php bin/console

# Sous WSL, PhpStorm place son Node Windows en tête du PATH : on retire les
# entrées /mnt/* pour utiliser le Node/npm Linux.
export PATH := $(shell printf '%s' "$$PATH" | tr ':' '\n' | grep -v '^/mnt/' | paste -sd:)

.DEFAULT_GOAL := help
.PHONY: help install start stop logs dev build \
        controller entity crud form \
        migration migrate fixtures db-reset \
        test lint cc

help: ## Affiche cette aide
	@grep -hE '^[a-zA-Z_-]+:.*?## ' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[33m%-12s\033[0m %s\n", $$1, $$2}'

## —— Projet ——
install: ## Installe les dépendances PHP et JS
	composer install
	npm install

start: ## Lance le serveur Symfony en arrière-plan
	symfony serve -d

stop: ## Arrête le serveur Symfony
	symfony server:stop

logs: ## Affiche les logs du serveur Symfony
	symfony server:log

dev: ## Lance Vite en mode dev (rechargement à chaud)
	npm run dev

build: ## Compile les assets pour la production
	npm run build

## —— Génération de code (maker) ——
controller: ## Crée un contrôleur (make controller name=Home)
	$(CONSOLE) make:controller $(name)

entity: ## Crée ou complète une entité (make entity name=Listing)
	$(CONSOLE) make:entity $(name)

crud: ## Génère un CRUD pour une entité (make crud name=Listing)
	$(CONSOLE) make:crud $(name)

form: ## Crée un formulaire (make form name=Listing)
	$(CONSOLE) make:form $(name)

## —— Base de données ——
migration: ## Génère une migration à partir des entités
	$(CONSOLE) make:migration

migrate: ## Exécute les migrations
	$(CONSOLE) doctrine:migrations:migrate --no-interaction

fixtures: ## Recharge les fixtures (vide la base)
	$(CONSOLE) doctrine:fixtures:load --no-interaction

db-reset: ## Recrée la base, joue les migrations et les fixtures
	$(CONSOLE) doctrine:database:drop --force --if-exists
	$(CONSOLE) doctrine:database:create
	$(MAKE) migrate fixtures

## —— Qualité ——
test: ## Lance les tests PHPUnit
	php bin/phpunit

lint: ## Vérifie les templates Twig, le YAML, le conteneur et le schéma
	$(CONSOLE) lint:twig templates
	$(CONSOLE) lint:yaml config
	$(CONSOLE) lint:container
	$(CONSOLE) doctrine:schema:validate

cc: ## Vide le cache
	$(CONSOLE) cache:clear
