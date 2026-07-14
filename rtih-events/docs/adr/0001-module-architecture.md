# ADR 0001: Modular Architecture Transition

## Status
Accepted

## Context
The RTIH Events platform has grown beyond a simple monolithic CRUD application. As we scale to support additional RTIH initiatives (Visitor Management, Mentorship, Incubation, Resource Booking), managing all controllers, models, and views in the default Laravel root namespaces (`app/Http/Controllers`, `app/Models`) will lead to an unmaintainable "Big Ball of Mud."
We need an architectural strategy that allows discrete domains to be developed, tested, and maintained in isolation while sharing a unified core platform.

## Decision
We will transition the platform from a standard Laravel monolith to a **Modular Monolith** using Domain-Driven Design (DDD) principles.

1. **The `app/Modules` Directory**: All new features and domain logic will reside under `app/Modules/{ModuleName}`.
2. **The Core Module**: Shared interfaces, traits, DTOs, Enums, and base services will live in `app/Modules/Core`. Other modules will depend on Core, but Core will never depend on specific feature modules.
3. **Module Structure**: A typical module (e.g., `Applications`) will contain its own `Controllers`, `Models`, `Services`, `Events`, and `Listeners`.
4. **Gradual Migration**: We will not perform a "big bang" rewrite. Existing features will remain in the standard Laravel directories until they are touched for major upgrades. New features and the targeted pilot workflow (Applications) will adopt this modular structure first.

## Consequences
- **Pros**: Clearer domain boundaries, easier onboarding for new developers, reduced risk of unintended side-effects when modifying features, and a clear path to microservices if ever required.
- **Cons**: Increased initial overhead for setting up new features, learning curve for developers accustomed strictly to standard Laravel directory structures.
