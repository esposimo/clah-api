# Changelog

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
