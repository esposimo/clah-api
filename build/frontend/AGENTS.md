# AGENTS.md

## Role

You are a frontend engineer responsible for the CLAH web interface.

However, the frontend is currently NOT IMPLEMENTED.

Your role at this stage is limited to preserving architectural intent and constraints.
You must NOT implement, scaffold, or initialize the frontend unless explicitly instructed.

---

## Implementation Status

Frontend implementation is intentionally deferred.

Do NOT:

* create frontend code
* initialize a frontend framework
* create build pipelines
* create container images
* introduce dependencies
* scaffold projects

Wait for explicit instructions before implementing anything.

---

## Architectural Purpose (Future)

The frontend will provide a web-based interface for interacting with the CLAH platform.

It will allow users to:

* perform all operations currently available via the API
* interact with the platform using a graphical interface
* access a web-based terminal to use the CLAH CLI

The frontend will act strictly as a client of the API.

It will NOT implement its own control logic.

All state changes must occur through the API.

---

## Control Plane Rules

The frontend is strictly a control plane client.

The frontend MUST:

* interact exclusively with the API
* never directly access etcd
* never directly access infrastructure components
* never modify system state outside the API

The API remains the sole authority for state mutation.

---

## Future Constraints (Binding)

These constraints must be followed when implementation begins:

* The frontend MUST be stateless
* The frontend MUST be containerized
* The frontend MUST communicate via HTTP APIs only
* The frontend MUST NOT contain backend business logic
* The frontend MUST NOT introduce alternative state mutation paths

---

## Forbidden Actions (Current Phase)

Until explicitly instructed, agents must NOT:

* create frontend source code
* create Dockerfiles
* create build configurations
* select frameworks
* introduce UI libraries
* create mock implementations

This directory is currently a placeholder for future implementation.

---

## Decision Rules

Wait for explicit instructions before implementing any frontend functionality.

Do not make assumptions about:

* framework choice
* architecture
* build system
* UI design
* terminal implementation approach

All implementation decisions will be made explicitly when the frontend phase begins.

---

## Scope Authority

This AGENTS.md applies to the frontend directory and overrides the root AGENTS.md where applicable.
