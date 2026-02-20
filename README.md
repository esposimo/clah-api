# CLAH (base bootstrap)

CLAH è una piattaforma containerizzata per gestire un cloud personale self-hosted in modo riproducibile.

## Stato attuale

Questo repository rappresenta il **nuovo punto di partenza** del progetto con una struttura base coerente con l'architettura:

- `build/kv-store`: servizio etcd (source of truth della configurazione)
- `build/api`: estensioni applicative Laravel su runtime PHP-FPM con base Laravel installata in build via Composer
- `build/rproxy`: reverse proxy Apache HTTPD con configurazione modulare
- `build/frontend`: placeholder (frontend non ancora implementato)

## Avvio locale

Prerequisiti:

- Docker
- Docker Compose v2

Comandi:

```bash
docker compose build
docker compose up -d
```

Verifiche rapide:

```bash
docker compose ps
docker compose logs -f kv-store api rproxy
```

## Note architetturali

- Docker Compose è l'unico orchestratore.
- etcd è il source of truth interno della piattaforma.
- Solo l'API è autorizzata a mutare lo stato della piattaforma.
- Il frontend è intenzionalmente rinviato e non viene ancora avviato.
