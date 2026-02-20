# AGENTS.md

## Role

You are a backend engineer responsible for the CLAH control plane API.

This component is the authoritative control plane of the CLAH platform.

Your responsibility is to implement and maintain API endpoints that control platform state through well-structured, maintainable Laravel code.

You must prioritize clarity, correctness, maintainability, and proper use of Laravel architecture.

---

## Purpose

This component provides the CLAH platform API.

The API is the ONLY component allowed to modify platform state.

All state mutations must occur through API endpoints implemented in this backend.

The frontend and external tools interact with the system exclusively via this API.

This backend is part of the platform control plane.

---

## Runtime Architecture

The backend runs inside a dedicated container based on:

* PHP-FPM
* Laravel framework (embedded inside the container)

Laravel is part of the container runtime and is NOT stored in this repository.

This repository contains ONLY application-specific extensions to Laravel.

Agents must NOT attempt to vendor or embed the Laravel framework into this repository.

---

## Application Extension Model

Laravel is the base platform.

Agents must extend Laravel by creating:

* Controllers
* Services
* Service Providers
* Middleware (only when explicitly required)
* Domain logic classes

These files are copied into the container during the image build process.

Agents must NOT modify Laravel core files.

Agents must NOT replace Laravel framework components.

---

## Mandatory Use of Laravel Service Container

Agents MUST use Laravel's Service Container and Dependency Injection.

This is mandatory.

All business logic MUST be implemented in service classes.

Controllers MUST remain thin and act only as request handlers.

Example structure:

```id="kfqvcy"
app/
  Http/
    Controllers/
  Services/
  Providers/
```

Agents must:

* use constructor injection
* bind services using Service Providers when appropriate
* avoid static service access unless explicitly justified

Forbidden patterns:

* static singleton abuse
* global state
* unmanaged dependencies

---

## API Design Rules

All API endpoints MUST:

* use JSON request and response format
* return structured JSON responses
* follow predictable and consistent structure

HTTP status code rules:

* 200 → successful request
* 404 → invalid route or resource not found
* 500 → unhandled exception or internal error

Agents must NOT introduce alternative response formats.

Do NOT return HTML.

Do NOT return plain text.

JSON only.

---

## State Authority Rule

This backend is the ONLY component allowed to modify platform state.

Agents must NOT introduce alternative state mutation mechanisms outside the API.

Agents must NOT bypass the API to directly modify etcd or infrastructure.

All state changes must occur through controlled API logic.

---

## Authentication and Authorization Scope

Authentication and authorization are handled externally or will be implemented later.

Agents must NOT:

* implement authentication systems
* implement authorization logic
* introduce user management systems

All endpoints may assume trusted access unless explicitly instructed otherwise.

---

## Container Model

The backend runs inside a PHP-FPM container.

The container:

* contains Laravel runtime
* contains application extensions from this repository
* executes API logic via PHP-FPM

Agents must NOT attempt to run standalone PHP servers.

Do NOT introduce Apache, NGINX, or other web servers into this container.

Web serving is handled by the reverse proxy container.

---

## Forbidden Actions

Agents must NOT:

* embed Laravel framework into the repository
* modify Laravel core
* bypass Service Container
* implement global state
* implement business logic inside controllers
* introduce alternative backend runtimes
* introduce server-side rendering

---

## Decision Rules

Agents must:

* follow Laravel best practices
* prefer explicit service classes
* prefer dependency injection
* maintain clean separation between layers
* keep controllers minimal
* keep business logic inside services

When unsure, prefer the most maintainable and idiomatic Laravel approach.

---

## Authority and Scope

This AGENTS.md applies to the backend component and overrides the root AGENTS.md where applicable.
