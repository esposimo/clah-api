# Reverse proxy build context

Reverse proxy Apache HTTPD con configurazione modulare reload-safe.

## Principi applicati

- include modulari (`conf.d` + `vhosts/*/vhost.conf`)
- esposizione API su path `/api` via VirtualHost dedicato
- logging separato per VirtualHost con rotazione oraria (`rotatelogs`)
- configurazione caricata da volumi esterni in Docker Compose
