.DEFAULT_GOAL := help

# ──────────────────────────────────────────────────────────────────────────────
# Configuration
# ──────────────────────────────────────────────────────────────────────────────
PROJECT_NAME   := HelloCSE
DOCKER         ?= 0
HELP_WIDTH     := 60

# Colors
COLOR_RESET    := \033[0m
COLOR_SECTION  := \033[1;33m
COLOR_TARGET   := \033[32m
COLOR_HEADER   := \033[1;37m\033[46m
COLOR_FOOTER   := \033[1;37m\033[44m

# Commands — set DOCKER=1 to run inside the container
ifeq ($(DOCKER),1)
    RUN  := docker compose exec app
    PHP  := docker compose exec app php
    COMP := docker compose exec app composer
    NPM  := docker compose exec app npm
else
    RUN  :=
    PHP  := php
    COMP := composer
    NPM  := npm
endif

ARTISAN  := $(PHP) artisan
PINT     := $(RUN) vendor/bin/pint
PHPSTAN  := $(RUN) vendor/bin/phpstan
RECTOR   := $(RUN) vendor/bin/rector

# ──────────────────────────────────────────────────────────────────────────────
# Help
# ──────────────────────────────────────────────────────────────────────────────
.PHONY: help
help: ## Show available commands
	@echo ""
	@printf "$(COLOR_HEADER)  %-$(HELP_WIDTH)s$(COLOR_RESET)\n" "$(PROJECT_NAME) — Available Commands"
	@echo ""
	@grep -hE '(^[[:alnum:]_-]+:.*?##.*$$)|(^## )' $(MAKEFILE_LIST) \
		| awk 'BEGIN {FS=":.*?## "} \
			/^## / { gsub(/^## /, "", $$0); printf "\n$(COLOR_SECTION)%s$(COLOR_RESET)\n", $$0; next } \
			{ printf "  $(COLOR_TARGET)%-28s$(COLOR_RESET) %s\n", $$1, $$2 }'
	@echo ""

# ──────────────────────────────────────────────────────────────────────────────
## Installation
# ──────────────────────────────────────────────────────────────────────────────
.PHONY: install
install: ## Install all dependencies (composer + npm)
	$(COMP) install
	$(NPM) ci

.PHONY: setup
setup: install ## Full project setup (install + key + migrate + seed + storage)
	cp -n .env.example .env || true
	$(ARTISAN) key:generate
	$(ARTISAN) migrate --seed
	$(ARTISAN) storage:link

# ──────────────────────────────────────────────────────────────────────────────
## Docker
# ──────────────────────────────────────────────────────────────────────────────
.PHONY: up
up: ## Start Docker containers
	docker compose up -d

.PHONY: down
down: ## Stop Docker containers
	docker compose down

.PHONY: build
build: ## Build Docker images
	docker compose build --no-cache

.PHONY: shell
shell: ## Open shell in app container
	docker compose exec app bash

# ──────────────────────────────────────────────────────────────────────────────
## Development
# ──────────────────────────────────────────────────────────────────────────────
.PHONY: dev
dev: ## Start dev server (php artisan serve + vite)
	$(COMP) run dev

.PHONY: migrate
migrate: ## Run database migrations
	$(ARTISAN) migrate

.PHONY: migrate-fresh
migrate-fresh: ## Drop all tables and re-run migrations
	$(ARTISAN) migrate:fresh

.PHONY: seed
seed: ## Seed the database
	$(ARTISAN) db:seed

.PHONY: fresh
fresh: ## Fresh migration with seeders
	$(ARTISAN) migrate:fresh --seed

# ──────────────────────────────────────────────────────────────────────────────
## Testing
# ──────────────────────────────────────────────────────────────────────────────
.PHONY: test
test: ## Run all tests
	$(ARTISAN) test

.PHONY: test-unit
test-unit: ## Run unit tests only
	$(ARTISAN) test --testsuite=Unit

.PHONY: test-feature
test-feature: ## Run feature tests only
	$(ARTISAN) test --testsuite=Feature

.PHONY: test-coverage
test-coverage: ## Run tests with HTML coverage report (requires Xdebug)
	XDEBUG_MODE=coverage $(ARTISAN) test --coverage --coverage-html=build/coverage

.PHONY: test-filter
test-filter: ## Run a specific test — usage: make test-filter F=MyTest
	$(ARTISAN) test --filter=$(F)

# ──────────────────────────────────────────────────────────────────────────────
## Code Style
# ──────────────────────────────────────────────────────────────────────────────
.PHONY: pint
pint: ## Check code style (Pint — dry run)
	$(PINT) --test

.PHONY: pint-fix
pint-fix: ## Fix code style automatically (Pint)
	$(PINT)

# ──────────────────────────────────────────────────────────────────────────────
## Static Analysis
# ──────────────────────────────────────────────────────────────────────────────
.PHONY: phpstan
phpstan: ## Run PHPStan static analysis
	$(PHPSTAN) analyse

.PHONY: phpstan-baseline
phpstan-baseline: ## Generate PHPStan baseline (accept current errors)
	$(PHPSTAN) analyse --generate-baseline

# ──────────────────────────────────────────────────────────────────────────────
## Code Modernization
# ──────────────────────────────────────────────────────────────────────────────
.PHONY: rector
rector: ## Check code modernization opportunities (dry run)
	$(RECTOR) --dry-run

.PHONY: rector-fix
rector-fix: ## Apply automatic code modernization
	$(RECTOR)

# ──────────────────────────────────────────────────────────────────────────────
## Quality Gates
# ──────────────────────────────────────────────────────────────────────────────
.PHONY: quality
quality: pint phpstan rector ## Run all quality checks (pint + phpstan + rector)

.PHONY: quality-fix
quality-fix: rector-fix pint-fix ## Auto-fix all fixable issues (rector → pint)

.PHONY: ci
ci: quality test ## Full CI validation (quality + tests)
	@echo ""
	@echo "✅ CI validation passed"
