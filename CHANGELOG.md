# Changelog

Tutte le modifiche rilevanti a questo progetto verranno documentate in questo file.

## [0.1.0] - 2026-02-10

### Added
- Inizializzata ossatura progetto con `docker-compose.yml`.
- Aggiunti build context e Dockerfile base per `api`, `kv-store`, `rproxy`, `frontend`.
- Aggiunti Caddyfile placeholder per `rproxy` e `frontend`.
- Aggiunto frontend placeholder statico.

### Changed
- Uniformati i nomi dei servizi nel compose con prefisso `clah-` (`clah-api`, `clah-kv-store`, `clah-rproxy`, `clah-frontend`).
