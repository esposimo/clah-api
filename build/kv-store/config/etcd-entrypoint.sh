#!/usr/bin/env sh
set -eu

. /etc/etcd/etcd.env

exec etcd \
  --name="${ETCD_NAME}" \
  --data-dir="${ETCD_DATA_DIR}" \
  --listen-client-urls="${ETCD_LISTEN_CLIENT_URLS}" \
  --advertise-client-urls="${ETCD_ADVERTISE_CLIENT_URLS}" \
  --listen-peer-urls="${ETCD_LISTEN_PEER_URLS}" \
  --initial-advertise-peer-urls="${ETCD_INITIAL_ADVERTISE_PEER_URLS}" \
  --initial-cluster="${ETCD_INITIAL_CLUSTER}" \
  --initial-cluster-state="${ETCD_INITIAL_CLUSTER_STATE}" \
  --initial-cluster-token="${ETCD_INITIAL_CLUSTER_TOKEN}"
