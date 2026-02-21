# Changelog

## [0.1.5] - 2026-02-21

### Added
- Aggiunto skeleton backend storage-agnostic in `build/api/app/Domain/RepositoryInterface.php` con operazioni KV (`get`, `put`, `del`, `list`).
- Introdotta l'implementazione etcd `build/api/app/Infrastructure/Storage/EtcdRepository.php` con integrazione API v3 (`/v3/kv/*`) per accesso al source of truth.
- Aggiunte le classi di dominio `Environment` e `EnvironmentRepository` per serializzazione, salvataggio, eliminazione, lookup per id/nome e lista ambienti.
- Aggiunto binding Laravel DI in `build/api/app/Providers/AppServiceProvider.php` tra `RepositoryInterface` e `EtcdRepository`.

### Changed
- Aggiunto `build/api/app/Http/Controllers/EnvironmentController.php` con metodi `list`, `show`, `add`, `del` allineati alle azioni OpenAPI e gestione logica di cancellazione lato controller.
- Aggiornato `build/api/Dockerfile` per includere i nuovi namespace `Domain`, `Infrastructure`, il provider applicativo e il nuovo controller nel runtime Laravel.
- Aggiornata la documentazione in `build/api/README.md` e `README.md` con il nuovo skeleton repository/storage per i controller API.

## [0.1.4] - 2026-02-21

### Changed
- Aggiornata la specifica `build/api/openapi.json` per l'oggetto ambiente con i nuovi attributi `friendly-name` (max 30 caratteri) e `description` (max 1024 caratteri).
- Aggiunto vincolo OpenAPI su `name` per accettare solo lettere, numeri e i caratteri `.`, `_`, `-` (senza spazi) tramite pattern `^[A-Za-z0-9._-]+$`.
- Allineata la documentazione API in `build/api/README.md` ai nuovi vincoli/schema degli ambienti.

## [0.1.3] - 2026-02-21

### Changed
- Estesa la specifica `build/api/openapi.json` per `/env` con operazioni CRUD a livello OpenAPI: list (`GET /env`), show (`GET /env/{uuid}`), add (`POST /env`) e del (`DELETE /env/{uuid}`).
- Definiti gli schemi JSON degli ambienti (`name`, `uuid`) e delle risposte per lista (`count` + `items`) ed eliminazione (`status: ok`).
- Aggiornata la documentazione API in `build/api/README.md` per riflettere i nuovi endpoint documentati.

## [0.1.2] - 2026-02-20

### Added
- Aggiunta specifica `build/api/openapi.json` con i primi endpoint `GET /env` e `GET /networks`.
- Aggiornata la documentazione API e repository per includere la nuova base OpenAPI.

## [0.1.1] - 2026-02-20

### Changed
- Aggiornato `build/api/Dockerfile` a modello multi-stage con installazione Laravel tramite Composer in build.
- Rimosso il setup `pdo`/`pdo_mysql` non necessario dal container API baseline.
- Ridotte le `COPY` API a file di estensione espliciti per evitare sovrascritture involontarie della base Laravel.
- Semplificato `build/rproxy/Dockerfile` rimuovendo installazione `logrotate` non necessaria.

## [0.1.0] - 2026-02-20

### Added
- Inizializzata la struttura base del progetto CLAH.
- Aggiunta orchestrazione Docker Compose con servizi `kv-store`, `api` e `rproxy`.
- Aggiunta base build context per etcd, API control plane e reverse proxy Apache.
- Aggiunta documentazione operativa iniziale (`README.md`).
