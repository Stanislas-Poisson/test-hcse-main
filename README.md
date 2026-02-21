# Test technique senior — HelloCSE (Laravel)

Bienvenue ! Ce dépôt sert de base à un test technique destiné à un·e développeur·se senior PHP/Laravel.
Votre mission est d'améliorer techniquement l'application existante autour de la gestion d'offres et de produits.

## Objectif général

- Apporter des améliorations structurelles et de qualité au projet (architecture, tests, qualité de code) tout en conservant le fonctionnement existant.
- L'enjeu est d'évaluer votre capacité à raisonner, structurer, sécuriser et tester un code Laravel dans un contexte proche de la production.

## Contenu actuel du projet (à connaître)
- Back-office simple de gestion d'offres et des produits liés à une offre.
- API publique GET /api/offers retournant uniquement les offres et produits publiés.

## Ce que nous attendons (périmètre minimal)

- Temps indicatif réalisation : 3 à 8 heures.
- Pas de sur-investissement UI/Design. Restez focalisé sur la qualité backend et l'ingénierie.
- Préférez des améliorations progressives et pragmatiques à une réécriture totale.

1) Architecture et séparation des responsabilités
   - Extraire le code métier dans des services/domain pour découpler la couche HTTP de la logique métier.
   - Introduire si nécessaire des classes dédiées (ex: Actions/Services, DTO, Repositories, Query Objects) avec un design clair, testable et documenté.

2) Qualité de code et outillage
   - PHPStan niveau 8 minimum (viser 9 si pertinent) et correction des erreurs remontées.
   - Ajouter/Configurer d'autres outils que vous jugez pertinents (ex: Larastan, PHP-CS-Fixer/Pint, Psalm, Laravel Pint, Rector) avec une configuration minimale et reproductible.
   - Respect des conventions (PSR-12, nommage, règles de complexité raisonnables, petites méthodes, dépendances explicites).

3) Tests
   - Écrire des tests unitaires PHPUnit ciblant la logique métier extraite (services, règles d'état, validations métiers, etc.).
   - Ajouter des tests de feature pertinents (ex: endpoints, règles d'accès, flux critiques).
   - Viser une couverture utile et significative sur les parties clés (pas de « test pour tester »).

4) Données & démos
   - Ajouter des seeders pour fournir un jeu de données de démonstration cohérent (offres + produits, états variés, images simulées si besoin).
   - Veiller à ce que l'appli soit rapidement exploitable après installation (un développeur doit voir une UI et des données en quelques commandes).

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
- Extraire la logique liée aux états (transitions possibles, règles d'affichage, filtrages par défaut)
- Politique de sécurité (Policies/Gates), middleware d'auth, rate limiting, validation d'input stricte.
- Documentation API (OpenAPI/Swagger), versionnement API, pagination/tri/filtrage RESTful.
- Optimisations perfs (index DB, N+1, caches, Eager Loading par défaut, Scopes).
- CI (GitHub Actions) exécutant lint + static analysis + tests.
- Docker/Sail prêt à l'emploi, Makefile ou scripts pour simplifier les commandes.
- Observers, Events/Listeners, Notifications, Queues (jobs pour traitement d'images par ex.).

## Critères d'évaluation
- Clarté de l'architecture, découpage des responsabilités, lisibilité.
- Qualité des tests (pertinence, couverture utile, isolation, fidélité à la logique métier).
- Niveau de qualité de code (typages, immutabilité quand pertinent, complexité maîtrisée, cohérence globale, commentaires ciblés).
- Robustesse des choix techniques (validation, gestion des états, gestion fichiers, erreurs, sécurité basique).
- Expérience de dev et reproductibilité (setup simple, scripts, doc, seeders, cohérence des environnements).
- Pertinence des bonus si présents (pas nécessaire d'en faire beaucoup; qualité > quantité).

## Consignes de rendu
- Travaillez dans une branche dédiée et ouvrez une Pull Request (ou fournissez un patch) expliquée clairement.
- Commits atomiques et messages explicites.
- Ajoutez/éditez ce README pour décrire vos choix techniques: architecture, services, tests, outillage, limites connues et pistes d'amélioration.
- Si vous ajoutez d'autres outils (Pint, Psalm, Rector…), documentez les commandes dans ce README ou un Makefile.
- Indiquez le temps passé et ce que vous auriez fait avec plus de temps.

## Questions
Si un point n'est pas clair, documentez vos hypothèses directement dans la PR/README et avancez. Vous pouvez proposer des alternatives techniques et expliquer vos arbitrages.

Bon courage et merci !

---

## Rendu — Stanislas Poisson

### Installation rapide

**Avec Docker (recommandé) :**

```bash
make up        # démarre PHP, MySQL, Mailpit
make setup     # install + migrations + seed + storage:link
```

**Sans Docker (PHP local) :**

```bash
composer install && npm ci
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Accès : `http://localhost` (Docker) ou `http://localhost:8000` (local)
Back-office : `admin@hellocse.fr` / `password`
API publique : `GET /api/offers`

---

### Commandes du Makefile

```bash
make help          # liste toutes les commandes disponibles

# Qualité
make pint          # vérification du style (dry-run)
make pint-fix      # correction automatique du style
make phpstan       # analyse statique niveau 8
make rector        # détection des améliorations possibles (dry-run)
make rector-fix    # application des règles Rector
make quality       # pint + phpstan + rector (dry-run)
make quality-fix   # rector-fix + pint-fix

# Tests
make test          # tous les tests
make test-unit     # tests unitaires uniquement
make test-feature  # tests de feature uniquement
make test-filter F=CatalogueQueryTest  # test ciblé

# CI
make ci            # quality + tests (équivalent au pipeline GitHub Actions)

# Docker
make shell         # accès au shell du conteneur
make fresh         # migrate:fresh --seed (reset BDD)
```

Préfixez `DOCKER=1 make <target>` pour exécuter dans le conteneur.

---

### Architecture et décisions techniques

#### Problème central identifié

Les règles de visibilité du catalogue public étaient éclatées : la requête inline dans `Api/OfferController` filtrait les offres publiées, mais sans garantie que les produits associés soient eux aussi filtrés. Aucun concept ne portait explicitement la règle *"une offre publiée avec ses produits publiés"*.

#### Ce qui a été fait

**1. Centralisation de la visibilité — `CatalogueQuery`**

`app/Queries/CatalogueQuery.php` est la source de vérité unique pour les règles de visibilité publique. C'est un Query Object qui encapsule la logique sans être un fourre-tout Service :

```php
// Un seul endroit décide ce que voit le public
Offer::published()
    ->with(['products' => fn ($q) => $q->published()])
    ->get();
```

Toute évolution des règles de publication n'a qu'un point de modification.

**2. États explicites — `OfferState` / `ProductState`**

Les états étaient des chaînes brutes (`'published'`, `'draft'`). Deux backed enums PHP 8.1 les remplacent avec :
- Typage fort (impossible de passer un état invalide)
- `label()` pour l'affichage UI
- `options()` pour les selects de formulaire
- Scopes `scopePublished()` sur les models

**3. Contract d'API stable — `OfferResource` / `ProductResource`**

L'API exposait directement les models Eloquent. Les Resources définissent un contrat explicite : champs exposés, sérialisation de l'enum en string, exclusion des champs internes (`offer_id`, timestamps).

**4. Validation métier — FormRequests**

Quatre FormRequests (`StoreOffer`, `UpdateOffer`, `StoreProduct`, `UpdateProduct`) extraient la validation des contrôleurs. Points notables :
- Unicité de `slug` et `sku` avec `Rule::unique()->ignore()` à l'update
- `Enum(OfferState::class)` pour rejeter les états invalides
- Images obligatoires à la création, optionnelles à la mise à jour

**5. Tests de caractérisation en premier**

Avant toute modification, des tests ont été écrits pour verrouiller le comportement existant (`GetOffersTest`, `OfferAccessTest`). Le refactoring s'est fait avec le filet de sécurité en place.

#### Navigation dans le code

| Concept | Localisation |
|---|---|
| Règle de visibilité publique | `app/Queries/CatalogueQuery.php` |
| États offre / produit | `app/Enums/OfferState.php`, `app/Enums/ProductState.php` |
| API publique | `app/Http/Controllers/Api/OfferController.php` |
| Transformations API | `app/Http/Resources/` |
| Validations | `app/Http/Requests/` |
| Tests API | `tests/Feature/Api/GetOffersTest.php` |
| Tests back-office | `tests/Feature/BackOffice/OfferAccessTest.php` |
| Tests unitaires visibilité | `tests/Unit/CatalogueQueryTest.php` |
| Données de démo | `database/seeders/OfferSeeder.php` |

---

### Qualité de code

Outillage configuré et intégré au Makefile et au pipeline CI :

| Outil | Configuration | Résultat |
|---|---|---|
| PHPStan + Larastan | `phpstan.neon` — niveau 8 | 0 erreur (baseline pour le code Breeze généré) |
| Laravel Pint | `pint.json` — preset Laravel | 0 violation |
| Rector | `rector.php` — PHP 8.4 + Laravel 11 | 0 suggestion |
| PHPInsights | `config/insights.php` | 96.7% Code · 100% Complexity · 100% Architecture · 93.9% Style |

---

### Tests

51 tests, 126 assertions — tous en SQLite in-memory (rapides, sans dépendance externe).

| Suite | Fichier | Ce qu'il vérifie |
|---|---|---|
| Feature / API | `GetOffersTest` | Visibilité (offre publiée, draft, hidden, produits non publiés exclus), structure JSON, contrat des champs |
| Feature / BackOffice | `OfferAccessTest` | Redirection des guests, accès authentifié aux pages CRUD |
| Unit | `CatalogueQueryTest` | `CatalogueQuery` : filtrage par état, eager loading des produits, exclusion des non-publiés |

---

### Données de démonstration

Le seeder couvre les 5 cas de visibilité pertinents :

| Offre | État offre | Produits | Visible dans l'API ? |
|---|---|---|---|
| Offre avec tout publié | Published | Published | ✅ oui |
| Offre publiée, produits mixtes | Published | Published + Draft + Invisible | ✅ oui (seul publié visible) |
| Offre publiée sans produits | Published | aucun | ✅ oui (tableau vide) |
| Offre en brouillon | Draft | Published | ❌ non |
| Offre cachée | Hidden | Published | ❌ non |

---

### Ce qui n'a pas été fait (volontairement)

- **Pagination** sur l'API : l'endpoint `GET /api/offers` retourne tout. À ajouter dès que le volume le justifie, le contrat JSON changerait.
- **Policies/Gates** : le back-office est protégé par l'authentification Laravel standard. Aucun système de rôles n'a été introduit, le besoin n'étant pas spécifié.
- **Transitions d'état explicites** : les états sont librement modifiables depuis le back-office. Une machine à états (ex. `draft → published`, sans retour possible à `draft`) serait pertinente en production mais relèverait d'une spécification fonctionnelle à confirmer.
- **Swagger/OpenAPI** : la structure de l'API est documentée via les Resources et les tests. Une spec formelle serait à ajouter si l'API est consommée par des tiers.
- **Queues pour les images** : le traitement d'image est synchrone. Un job asynchrone serait préférable pour les uploads lourds.
- **N+1 côté back-office** : la liste des offres et des produits n'utilise pas d'eager loading systématique (hors `CatalogueQuery`). Les index DB sont absents.

---

### Ce qui serait fait avec plus de temps

- **Politique d'accès** (`OfferPolicy`, `ProductPolicy`) pour autoriser les actions par rôle.
- **Transitions d'état** modélisées explicitement avec des garde-fous métier.
- **Pagination + filtres RESTful** sur l'API publique.
- **Suppression physique des images** orphelines lors du remplacement ou de la suppression d'une entité.
- **Versionnement API** (`/api/v1/offers`) pour permettre des évolutions sans casse.
- **Tests de mutation** (PestPHP + Infection) pour valider la pertinence des tests existants.

---

### Outillage IA

Claude Code (Anthropic) a été utilisé comme copilote tout au long de l'exercice — génération de code, suggestions d'architecture, corrections PHPStan. Chaque décision technique a été guidée, validée et comprise avant intégration.
