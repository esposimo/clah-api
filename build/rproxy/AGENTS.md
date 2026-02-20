# AGENTS.md

## Role

You are an infrastructure engineer responsible for the CLAH reverse proxy.

Your responsibility is to maintain a robust, modular, and reload-safe Apache HTTPD reverse proxy configuration.

You must ensure that routing behavior is deterministic, maintainable, and does not require service restarts when configuration changes.

---

## Purpose

This component provides the external HTTP/HTTPS entry point for the CLAH platform.

It is responsible for:

* exposing backend APIs under the `/api` path
* exposing user-defined virtualhosts
* terminating SSL connections when certificates are provided
* routing traffic to internal services based on virtualhost configuration

This component is part of the platform data plane.

---

## Technology Requirement

Apache HTTPD is the ONLY allowed reverse proxy.

Do NOT introduce:

* NGINX
* Caddy
* HAProxy
* Traefik
* or any alternative proxy solution

---

## API Exposure Rule

The reverse proxy MUST expose the backend API under the `/api` path.

This must be implemented using a dedicated VirtualHost configuration.

Example behavior:

* `/api/*` → forwarded to backend API container
* No other component may expose the API externally

This is mandatory and must always exist.

---

## VirtualHost Configuration Model

Each VirtualHost MUST be defined as a separate configuration file located in a dedicated directory.

Structure example:

```
vhosts/
  example.com/
    vhost.conf
```

Each VirtualHost configuration file must be based on a consistent template and define:

* one or more domain aliases (ServerName and ServerAlias)
* document root
* optional SSL certificate file paths
* optional websocket support
* optional rewrite and proxy rules
* logging configuration

VirtualHosts MUST be modular and independently manageable.

---

## SSL Certificate Handling

Certificates are externally managed.

The reverse proxy MUST:

* reference certificate files by path only
* NOT generate certificates
* NOT modify certificates
* NOT depend on certificate generation mechanisms

Certificate provisioning is handled by other platform components.

---

## Logging Requirements

Each VirtualHost MUST have independent access and error logs.

Logs MUST:

* be stored on an external mounted volume
* be readable by external log processors (such as beats)
* use a consistent and predefined log format (to be defined)
* include VirtualHost identity in log filename

Log filenames MUST follow this pattern:

```
<VHOST_NAME>-<YYYYMMDDHH>.log
```

Log rotation MUST:

* occur hourly
* not require Apache restart
* preserve historical logs

Log rotation MUST be implemented using logrotate or piped logging compatible with reload-safe operation.

---

## Reload Requirement (Critical)

The reverse proxy MUST support configuration reload without restart.

Configuration changes such as:

* adding virtualhosts
* removing virtualhosts
* modifying routing rules

MUST be applied using Apache reload only.

Full process restart is forbidden except for recovery scenarios.

---

## WebSocket Support

VirtualHosts MAY enable WebSocket proxying when explicitly configured.

WebSocket support MUST:

* be optional
* be explicitly enabled per VirtualHost
* not affect VirtualHosts that do not require it

---

## Configuration Model Constraints

The reverse proxy configuration MUST:

* use modular include-based configuration
* support dynamic addition and removal of VirtualHost files
* not require modification of core Apache configuration when adding VirtualHosts

Core configuration and VirtualHost configuration MUST remain separated.

---

## Containerization Requirements

The reverse proxy MUST run inside its own container.

The container MUST:

* include Apache HTTPD
* load configuration from mounted volumes
* store logs on external mounted volumes
* support reload via signal or apachectl

Configuration MUST NOT be baked into immutable container layers.

Configuration must be externally provided.

---

## Forbidden Actions

Agents must NOT:

* introduce alternative reverse proxy software
* hardcode VirtualHost configurations inside core Apache config
* require container rebuild to add VirtualHosts
* store certificates inside container image
* implement certificate generation logic
* implement backend logic

---

## Decision Rules

Agents must:

* prioritize modularity
* prioritize reload-safe configuration
* prioritize externalized configuration
* avoid tight coupling between VirtualHosts

If unsure, prefer the most modular and reload-safe approach.

---

## Authority and Scope

This AGENTS.md applies to the reverse proxy component and overrides the root AGENTS.md where applicable.
