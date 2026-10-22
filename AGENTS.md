# Project Instructions

## Stack and layout

- Laravel 12.48.1 (Composer constraint `^12.0`), PHP `^8.2` (Docker uses PHP 8.4), Livewire 3.7.6, Filament 4, Tailwind CSS 4, and Vite.
- The Laravel project root is `app/`; run Artisan, Composer, and npm commands there locally. Docker commands below run from the repository root.
- Follow the existing Laravel architecture and keep business logic out of Blade views.

## Architecture

- HTTP routes are grouped by concern in `app/routes/`: auth, ecommerce, services, user dashboard, seller dashboard, and general web routes. `App\Providers\RouteServiceProvider` assigns middleware to these groups.
- Keep request handling in controllers, persistence/domain behavior in Eloquent models, and reusable workflows/calculations in `app/app/Services/`. Ecommerce models are grouped under `App\Models\Ecommerce`.
- Livewire 3 components live under `app/app/Livewire/` with corresponding templates under `app/resources/views/livewire/`. Use component methods for server-side actions, validate input, and follow existing event/attribute patterns.
- Filament admin functionality is under `app/app/Filament/`. Check the existing legacy integration and Composer autoload mappings before changing legacy behavior.

## Blade and reusable UI

- Anonymous Blade components live under `app/resources/views/components/` and are invoked as `<x-...>`; reusable class-backed components live under `app/app/View/Components/`.
- Before creating UI, look for existing components: layouts, forms and inputs, alerts, buttons, typography, tables, pagination, headers, product cards, and dashboard/ecommerce components.
- Configure anonymous components with `@props`; forward caller attributes with `$attributes->class(...)` or the existing merge pattern, and preserve slots and component attributes.
- The app layout renders the `styles` and `scripts` stacks. Follow existing `@pushOnce` patterns for component-specific CSS or scripts.
- Shared helpers are in `app/app/Helpers/`: `App`, `EcommerceHelper`, `FileHelper`, and `StringHelper`. Global shorthand functions are loaded from `Helpers/Shorthands/` by `AppServiceProvider`; check for existing helpers such as `user()`, `cart()`, and `price()` before adding another.

## Styling and JavaScript

- Vite inputs are `app/resources/css/app.css` and `app/resources/js/app.js`. `app.css` imports Tailwind, `base.css`, and `theme.css`, and scans Blade views.
- Tailwind v4 design tokens (colors, fonts, spacing, breakpoints, and light/dark values) are defined in `app/resources/css/theme.css`; shared layout utilities are in `base.css`. Reuse these tokens and patterns rather than adding duplicate values.
- Keep frontend behavior in the existing vanilla JavaScript modules or Alpine.js where appropriate. Do not introduce Vue, React, or Inertia.
- Alway place grandient inside `@pushOnce('styles')` tags instead of using Tailwind classes.

## Docker, build, and tests

From the repository root:

```bash
docker compose up --build -d
docker compose logs -f app vite db
docker compose exec vite npm run build
docker compose exec app php artisan test
```

Compose provides the Laravel app on port 8000, Vite on 5173, and MariaDB. The Vite service starts the development server automatically. Tests are PHPUnit/Pest tests under `app/tests/`; use `php artisan test` for the suite or pass a test path/filter when targeting specific tests.

## Working practices

- Inspect nearby code and reuse existing components, helpers, validation, authorization, and test patterns.
- Keep changes focused; do not modify migrations, dependencies, or environment configuration unless required.
- Run the narrowest relevant tests and build after application changes.
- Never generate images.
- Add or update tests relative to the changes you make even when nobody asks
- To run tests always use `docker compose exec -e APP_ENV=testing app php artisan test`
