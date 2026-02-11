# AGENTS.md

## Scope
This directory contains the **Key-Value Store service** for the project, based on **etcd**.

The kv-store is a **core infrastructure component** and is intended to be used by other services
(e.g. service discovery, configuration, coordination, locks).

Agents working in this directory must treat this component as **stateful and critical**.

---

## Responsibilities of this component
- Provide a reliable **distributed key-value store**
- Expose a stable **etcd API (v3)**
- Persist data across container restarts
- Be usable by other containers inside the project network
- Support future extensions (auth, TLS, clustering)

---

## What an agent MAY do
- Modify:
  - `Dockerfile`
  - `docker-compose.yml` or equivalent orchestration files
  - etcd configuration files
- Improve:
  - persistence handling (volumes, paths)
  - startup flags and tuning
  - healthchecks
- Add:
  - documentation
  - comments explaining configuration choices
  - optional scripts for initialization or checks
- Refactor configuration **without changing semantics**

---

## What an agent MUST NOT do
- Do **NOT** change data directories without explicit migration logic
- Do **NOT** wipe or reinitialize the store automatically
- Do **NOT** introduce breaking API changes
- Do **NOT** assume single-node forever unless explicitly stated
- Do **NOT** add application-specific keys or business logic

This service must remain **generic and reusable**.

---

## Persistence rules
- Data must be stored on a **persistent volume**
- The default etcd data directory must be explicit and documented
- Container restarts must **not** cause data loss
- Initialization logic must be **idempotent**

---

## Networking assumptions
- The service runs on an **internal network**
- It is not exposed publicly by default
- Other containers access it via service name / internal DNS
- Ports must be clearly documented

---

## Configuration principles
- Prefer explicit flags over implicit defaults
- Avoid unnecessary complexity
- Configuration must be readable without deep etcd knowledge
- Security features (auth, TLS) should be optional and disabled by default unless required

---

## Testing expectations
Agents should:
- Verify that etcd starts cleanly
- Verify basic read/write operations
- Verify persistence after restart
- Avoid destructive tests unless explicitly requested

---

## Mental model for agents
Think of this component as:
> “Infrastructure memory for the whole system”

If it breaks or resets, **everything above it becomes unreliable**.

When in doubt:
- prefer safety over convenience
- prefer clarity over cleverness
- ask before changing behavior
