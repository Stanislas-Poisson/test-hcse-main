# Test technique senior — HelloCSE (Laravel)

Bienvenue ! Ce dépôt sert de base à un test technique destiné à un·e développeur·se senior PHP/Laravel.
Votre mission est d’améliorer techniquement l’application existante autour de la gestion d’offres et de produits.

## Objectif général

- Apporter des améliorations structurelles et de qualité au projet (architecture, tests, qualité de code) tout en conservant le fonctionnement existant.
- L’enjeu est d’évaluer votre capacité à raisonner, structurer, sécuriser et tester un code Laravel dans un contexte proche de la production.

## Contenu actuel du projet (à connaître)
- Back-office simple de gestion d’offres et des produits liés à une offre.
- API publique GET /api/offers retournant uniquement les offres et produits publiés.

## Ce que nous attendons (périmètre minimal)

- Temps indicatif réalisation : 3 à 8 heures.
- Pas de sur-investissement UI/Design. Restez focalisé sur la qualité backend et l’ingénierie.
- Préférez des améliorations progressives et pragmatiques à une réécriture totale.

1) Architecture et séparation des responsabilités
   - Extraire le code métier dans des services/domain pour découpler la couche HTTP de la logique métier.
   - Introduire si nécessaire des classes dédiées (ex: Actions/Services, DTO, Repositories, Query Objects) avec un design clair, testable et documenté.

2) Qualité de code et outillage
   - PHPStan niveau 8 minimum (viser 9 si pertinent) et correction des erreurs remontées.
   - Ajouter/Configurer d’autres outils que vous jugez pertinents (ex: Larastan, PHP-CS-Fixer/Pint, Psalm, Laravel Pint, Rector) avec une configuration minimale et reproductible.
   - Respect des conventions (PSR-12, nommage, règles de complexité raisonnables, petites méthodes, dépendances explicites).

3) Tests
   - Écrire des tests unitaires PHPUnit ciblant la logique métier extraite (services, règles d’état, validations métiers, etc.).
   - Ajouter des tests de feature pertinents (ex: endpoints, règles d’accès, flux critiques).
   - Viser une couverture utile et significative sur les parties clés (pas de « test pour tester »).

4) Données & démos
   - Ajouter des seeders pour fournir un jeu de données de démonstration cohérent (offres + produits, états variés, images simulées si besoin).
   - Veiller à ce que l’appli soit rapidement exploitable après installation (un développeur doit voir une UI et des données en quelques commandes).

5) Robustesse
   - Gestion propre des validations (FormRequest, règles partagées, messages clairs).
   - Gestion des fichiers (images) sécurisée et robuste.
   - Pagination, tri et filtres côté back si nécessaire pour la scalabilité.
   - API Resources/Transformers pour les réponses API (contract stable, filtrage des champs, sérialisation).

6) Documentation
   - Architecture et décisions clés
   - Comment lancer tests et outils
   - Comment naviguer dans le code

## Bonus appréciés (optionnels, choisissez selon le temps / pertinence)

- Patterns avancés (DDD light, Ports/Adapters, Repositories, Query Services, Specification, Value Objects).
- Extraire la logique liée aux états (transitions possibles, règles d’affichage, filtrages par défaut)
- Politique de sécurité (Policies/Gates), middleware d’auth, rate limiting, validation d’input stricte.
- Documentation API (OpenAPI/Swagger), versionnement API, pagination/tri/filtrage RESTful.
- Optimisations perfs (index DB, N+1, caches, Eager Loading par défaut, Scopes).
- CI (GitHub Actions) exécutant lint + static analysis + tests.
- Docker/Sail prêt à l’emploi, Makefile ou scripts pour simplifier les commandes.
- Observers, Events/Listeners, Notifications, Queues (jobs pour traitement d’images par ex.).

## Critères d’évaluation
- Clarté de l’architecture, découpage des responsabilités, lisibilité.
- Qualité des tests (pertinence, couverture utile, isolation, fidélité à la logique métier).
- Niveau de qualité de code (typages, immutabilité quand pertinent, complexité maîtrisée, cohérence globale, commentaires ciblés).
- Robustesse des choix techniques (validation, gestion des états, gestion fichiers, erreurs, sécurité basique).
- Expérience de dev et reproductibilité (setup simple, scripts, doc, seeders, cohérence des environnements).
- Pertinence des bonus si présents (pas nécessaire d’en faire beaucoup; qualité > quantité).

## Consignes de rendu
- Travaillez dans une branche dédiée et ouvrez une Pull Request (ou fournissez un patch) expliquée clairement.
- Commits atomiques et messages explicites.
- Ajoutez/éditez ce README pour décrire vos choix techniques: architecture, services, tests, outillage, limites connues et pistes d’amélioration.
- Si vous ajoutez d’autres outils (Pint, Psalm, Rector…), documentez les commandes dans ce README ou un Makefile.
- Indiquez le temps passé et ce que vous auriez fait avec plus de temps.

## Questions
Si un point n’est pas clair, documentez vos hypothèses directement dans la PR/README et avancez. Vous pouvez proposer des alternatives techniques et expliquer vos arbitrages.

Bon courage et merci !

## Environnement et installation
Prérequis
- PHP 8.4+
- Composer 2
- Node 18+ et npm
- MySQL/MariaDB (ou SQLite si vous préférez pour l’exercice)
- Optionnel: Docker + Laravel Sail

Étapes rapides (local hors Docker)
1. Cloner le repo et installer les dépendances
   - composer install
   - npm ci
2. Copier l’environnement
   - cp .env.example .env
   - Configurer la base de données (DB_*) et le stockage local.
3. Générer la clé d’application
   - php artisan key:generate
4. Exécuter les migrations et seeders
   - php artisan migrate --seed
5. Lier le stockage public
   - php artisan storage:link
6. Builder les assets (si UI utilisée)
   - npm run build (ou npm run dev pour le watch)
7. Lancer l’application
   - php artisan serve (ou via votre stack locale)

Étapes avec Sail (optionnel)
1. composer install && cp .env.example .env
2. ./vendor/bin/sail up -d
3. ./vendor/bin/sail artisan key:generate
4. ./vendor/bin/sail artisan migrate --seed
5. ./vendor/bin/sail artisan storage:link
6. ./vendor/bin/sail npm ci && ./vendor/bin/sail npm run build

Tests et qualité
- Lancer les tests: phpunit ou php artisan test
- Lancer PHPStan: vendor/bin/phpstan analyse --level=8 (ajustez le niveau si vous visez plus)

