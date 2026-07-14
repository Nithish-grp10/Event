# Contributing to RTIH EXP

We welcome contributions to the RTIH Experience Platform. To ensure a cohesive, enterprise-grade codebase, please adhere to the following workflow:

## 1. Modular First
Always consider if a new feature belongs in an existing module or requires a new one. Do not dump controllers into `app/Http/Controllers`. Use the `app/Modules/` directory.

## 2. Shared UI
If you need a new UI element, check `resources/views/components/ui/` first. If it does not exist, build it as a generic, reusable Blade component that consumes Tailwind Design Tokens. **Do not hardcode colors or border radii.**

## 3. Pull Requests
- Keep PRs scoped to a single feature or bug fix.
- Ensure all CI/CD pipelines (static analysis, tests) pass before requesting a review.
- Document any architectural shifts via Architecture Decision Records (`docs/adr/`).

## 4. Testing
New features must include accompanying automated tests (Feature and Unit tests).

For more detailed coding standards, please review:
- `CODE_STYLE.md`
- `COMPONENT_GUIDELINES.md`
- `MODULE_GUIDELINES.md`
