FROM quay.io/coreos/etcd:v3.5.15


COPY config/etcd.env /etc/etcd/etcd.env
#COPY --chmod=777 config/etcd-entrypoint.sh /usr/local/bin/etcd-entrypoint.sh

#RUN chmod +x /usr/local/bin/etcd-entrypoint.sh

EXPOSE 2379 2380
