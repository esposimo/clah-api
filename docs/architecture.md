# CLAH Architecture (Bootstrap)

## Componenti

- **kv-store (`build/kv-store`)**: etcd v3, source of truth della configurazione.
- **api (`build/api`)**: control plane applicativo, unico punto autorizzato per mutazioni di stato.
- **rproxy (`build/rproxy`)**: reverse proxy Apache, entry point HTTP/HTTPS.
- **frontend (`build/frontend`)**: non implementato (placeholder).

## Orchestrazione

- Orchestratore unico: **Docker Compose**.
- Rete interna condivisa: `clah_internal`.
- Persistenza stato etcd: volume `kv_store_data`.

## Regole di mutazione dello stato

- Solo `api` può orchestrare mutazioni dello stato della piattaforma.
- `kv-store` non contiene logica business e non è esposto pubblicamente.
- `rproxy` espone `/api` verso il servizio `api`.
