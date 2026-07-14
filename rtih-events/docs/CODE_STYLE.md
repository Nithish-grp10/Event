# Code Style Guidelines

## PHP Standards
- Follow PSR-12 coding standards.
- Use strict typing where applicable (`declare(strict_types=1);`).
- Add return types and parameter types to all methods and functions.
- Avoid deeply nested `if` statements. Return early (Guard clauses).

## Naming Conventions
- **Classes**: PascalCase (e.g., `EventService`, `ApplicationStatus`).
- **Methods/Functions**: camelCase (e.g., `calculateTotal()`).
- **Variables**: camelCase (e.g., `$eventCount`).
- **Database Tables**: snake_case, plural (e.g., `event_applications`).
- **Database Columns**: snake_case (e.g., `created_at`, `status_id`).
- **Enums**: PascalCase for enum names and cases (e.g., `ApplicationStatus::UnderReview`).

## Magic Values
- NEVER use magic strings or numbers. 
- Use PHP Enums (`app/Modules/Core/Enums/`) for statuses and types.
- Use Class Constants for predefined values that don't fit an enum.

## Eloquent
- Avoid N+1 queries. Always use eager loading (`with()`).
- Do not put raw SQL queries in controllers. Use Eloquent Scopes or Services.
