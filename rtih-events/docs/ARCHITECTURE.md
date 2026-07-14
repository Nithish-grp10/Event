# RTIH Experience Platform (EXP) v2.0 - Architecture

## Core Philosophy
RTIH EXP is built as a **Modular Enterprise Application** using Domain-Driven Design (DDD) principles. It is designed to scale and support multiple enterprise modules (Events, Applications, Visitor Management, Incubation) without devolving into a "Big Ball of Mud".

## Stack
- **Framework**: Laravel 11 / 12
- **Language**: PHP 8.4
- **Database**: PostgreSQL / MySQL
- **Frontend**: Blade + Alpine.js + Tailwind CSS (UI Primitives)
- **Authentication**: Laravel Sanctum (API) & Session (Web) + Spatie Permission (RBAC)

## Directory Structure
The platform is transitioning from a standard Laravel monolith to a Modular Monolith.
- `app/Modules/Core`: The foundational layer. Contains Shared Contracts, Traits, Helpers, Enums, DTOs, and foundational Services (e.g. AI, Storage, Search abstractions).
- `app/Modules/{Domain}`: Vertical slices of functionality (e.g. `Applications`, `Events`). Each module is self-contained with its own Controllers, Models, Services, and Events.
- `resources/views/components/ui/`: The exhaustive Shared UI Layer. Divided by domains (`buttons/`, `forms/`, `layout/`, `tables/`, etc.).

## Architectural Rules
1. **Thin Controllers**: Controllers should only parse HTTP requests and format responses. They delegate logic to `Services`.
2. **Domain Isolation**: A module (e.g., `Events`) may communicate with another module (e.g., `Applications`) via Domain Events or formal service contracts, avoiding deep coupling of database queries between domains.
3. **API-First Design**: Every feature should ideally have an API endpoint in `routes/api.php` utilizing API Resources, keeping the platform ready for headless and mobile consumption.
4. **No Magic Strings**: Use PHP 8.1 Enums (located in `app/Modules/Core/Enums`) instead of hardcoded strings for statuses, types, and roles.
