# CLAH API / Home Cloud (bootstrap)

Repository iniziale per costruire un piccolo "cloud casalingo" composto da più servizi containerizzati.

## Stack iniziale

- `clah-api`: backend Laravel (runtime PHP-FPM)
- `clah-kv-store`: etcd come source of truth per configurazioni/stato
- `clah-rproxy`: reverse proxy Caddy (entrypoint HTTP)
- `clah-frontend`: UI placeholder servita con Caddy

> Nota: il container `runner` per attività di deploy (docker cli, terraform, ecc.) è previsto ma non ancora incluso in questa prima ossatura.

## Struttura repository

```text
.
├── docker-compose.yml
├── CHANGELOG.md
├── README.md
└── build
    ├── api
    │   ├── Dockerfile
    │   └── src
    │       ├── app/Domain/{Environment,Network}
    │       ├── app/Services/{Etcd,Environment,Network}
    │       ├── app/Providers/AppServiceProvider.php
    │       ├── app/Http/Controllers/Api
    │       └── routes/api.php
    ├── frontend
    │   ├── Caddyfile
    │   ├── Dockerfile
    │   └── site/index.html
    ├── kv-store
    │   ├── Dockerfile
    └── rproxy
        ├── Caddyfile
        └── Dockerfile
```

## Avvio locale

```bash
docker compose up --build -d
```

Endpoint iniziali:
- Reverse proxy: `http://localhost:80`
- etcd client API: `http://localhost:2379`

## Prossimi passi

- definizione della logica interna dei controller Laravel per persistenza su etcd
- routing `/api` su backend Laravel via reverse proxy
- definizione API e modello stato su etcd
- introduzione container `runner` con profili compose
- scelta stack frontend (Vue/React/altro)

## Nota runtime Laravel

- Il container API mantiene il progetto Laravel completo generato in image build.
- In sviluppo Compose monta solo `app` e `routes` da `build/api/src` per non sovrascrivere `public/index.php` e gli altri file bootstrap Laravel.
