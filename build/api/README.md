# API build context

Questo build context prepara un runtime PHP-FPM con base Laravel installata in fase di build tramite Composer.

## Modello adottato

- stage `laravel-base`: installa Laravel con `composer create-project`
- stage runtime: copia la base Laravel e poi applica solo le estensioni CLAH
- nessuna dipendenza database installata nel container baseline

## Struttura estensioni repository

- `app/Http/Controllers`: controller applicativi aggiuntivi
- `app/Services`: servizi con logica di dominio
- `bootstrap/`: artefatti bootstrap applicativi


## Specifica OpenAPI

- `openapi.json`: specifica iniziale del control plane API.
- Endpoint documentati: CRUD ambienti su `/env` (`GET /env`, `GET /env/{uuid}`, `POST /env`, `DELETE /env/{uuid}`) e `GET /networks`.
- Da questa specifica verrà derivata la generazione dei controller nelle iterazioni successive.
- Schema ambiente (`Environment` / `EnvCreateRequest`) esteso con `friendly-name` (max 30) e `description` (max 1024), con vincolo su `name` (`^[A-Za-z0-9._-]+$`).
