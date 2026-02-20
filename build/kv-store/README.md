# KV Store (etcd)

Servizio etcd v3 usato come source of truth interno della piattaforma.

## Caratteristiche della base iniziale

- data dir esplicita: `/var/lib/etcd`
- persistenza gestita via volume Docker (`kv_store_data`)
- endpoint client interno: `http://kv-store:2379`
- configurazione dichiarativa tramite variabili in `config/etcd.env`
