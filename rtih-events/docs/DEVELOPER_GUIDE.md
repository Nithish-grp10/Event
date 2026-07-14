# Developer DX Guide

Welcome to the RTIH Platform team! 

## Getting Started
1. Clone the repository.
2. Run `composer install` & `npm install`.
3. Copy `.env.example` to `.env` and generate app key: `php artisan key:generate`.
4. Run migrations and seed the database: `php artisan migrate:fresh --seed`.

## Coding Standards
We enforce PSR-12 and Laravel's default code style. 
Before submitting a Pull Request, you **must** run:
```bash
./vendor/bin/pint
```
This will automatically fix most style violations.

## Static Analysis
We use Larastan to catch bugs before they hit production. 
Run the static analyzer with:
```bash
./vendor/bin/phpstan analyse
```
Ensure there are no errors on Level 5.

## UI Components
Do not write custom CSS classes for standard elements. Use the Blade UI primitives located in `resources/views/components/ui`.
Example:
```html
<x-ui.button variant="primary">Submit</x-ui.button>
```

## Testing
Always write Feature tests for new endpoints.
```bash
php artisan test
```
