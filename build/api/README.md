# API build context

Questo build context prepara un runtime PHP-FPM con base Laravel installata in fase di build tramite Composer.

## Modello adottato

- stage `laravel-base`: installa Laravel con `composer create-project`
- stage runtime: copia la base Laravel e poi applica solo le estensioni CLAH
- nessuna dipendenza database installata nel container baseline

## Struttura estensioni repository

- `app/Http/Controllers`: controller applicativi aggiuntivi (es. `HealthController`, `EnvironmentController`)
- `app/Domain`: entità e repository di dominio (storage-agnostic)
- `app/Infrastructure/Storage`: implementazioni concrete dei backend KV (es. etcd)
- `app/Providers`: binding del container Laravel
- `app/Services`: servizi con logica di dominio
- `bootstrap/`: artefatti bootstrap applicativi

## Skeleton storage per controller

- Definita `RepositoryInterface` con operazioni base KV (`get`, `put`, `del`, `list`).
- Introdotta `EtcdRepository` che implementa l'interfaccia e parla con etcd via API v3 HTTP/JSON.
- Registrato il binding nel container Laravel tramite `AppServiceProvider` (`RepositoryInterface -> EtcdRepository`).
- Aggiunti `Environment` e `EnvironmentRepository` per centralizzare serializzazione, persistenza e lookup degli ambienti.
- Aggiunto `EnvironmentController` con azioni OpenAPI (`list`, `show`, `add`, `del`) e logica di cancellazione centralizzata lato API.


## Specifica OpenAPI

- `openapi.json`: specifica iniziale del control plane API.
- Endpoint documentati: CRUD ambienti su `/env` (`GET /env`, `GET /env/{uuid}`, `POST /env`, `DELETE /env/{uuid}`) e `GET /networks`.
- Da questa specifica verrà derivata la generazione dei controller nelle iterazioni successive.
- Schema ambiente (`Environment` / `EnvCreateRequest`) esteso con `friendly-name` (max 30) e `description` (max 1024), con vincolo su `name` (`^[A-Za-z0-9._-]+$`).
