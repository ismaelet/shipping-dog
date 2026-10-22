# Project Instructions

## Stack and structure

- Laravel 12 (`^12.0`), PHP `^8.2` (Docker: 8.4), Livewire 3, Filament 4, Tailwind CSS 4, and Vite.
- Laravel root: `app/`. Run Artisan, Composer, and npm commands there locally; Docker commands run from the repository root.
- Follow existing Laravel conventions. Keep business logic out of Blade views.
- Routes are organized by concern in `app/routes/`, with middleware assigned by `App\Providers\RouteServiceProvider`.
- Controllers handle HTTP requests, Eloquent models handle persistence/domain behavior, and `app/app/Services/` contains reusable workflows and calculations.
- Ecommerce models live in `App\Models\Ecommerce`; Livewire components in `app/app/Livewire/`; Filament resources in `app/app/Filament/`.
- Livewire views live in `app/resources/views/livewire/`. Handle server-side actions in component methods, validate inputs, and reuse existing event/attribute patterns.
- Check legacy integration and Composer autoload mappings before modifying legacy code.

## Blade components and helpers

- Reuse existing Blade components before creating new ones, especially layouts, forms, buttons, typography, cards, tables, and pagination.
- Anonymous components live in `app/resources/views/components/` (`<x-...>`); class-backed components in `app/app/View/Components/`.
- Use `@props` for anonymous components and `$attributes->class(...)` or existing merge patterns to forward attributes. Preserve slots and caller attributes.
- Use `@pushOnce('styles')` and `@pushOnce('scripts')` for component-specific assets when needed; the app layout renders both stacks.
- Reuse helpers in `app/app/Helpers/` (`App`, `EcommerceHelper`, `FileHelper`, `StringHelper`) and global shorthands loaded by `AppServiceProvider` (e.g. `user()`, `cart()`, `price()`).
- For normal form elements try to use `<x-input/>`, `<x-select/>`,`<x-textarea/>` and `<x-button/>` when possible, instead of styling a native HTML component.

## Styling and design tokens

- Tailwind v4 configuration and design tokens are in `app/resources/css/theme.css`. Shared layout utilities are in `base.css`; `app.css` imports Tailwind and both files.
- **Always use semantic color tokens defined in `theme.css`. Never use Tailwind's default color palette or hardcoded/custom colors in components.**
- **Always use the exact token names, including their numeric suffixes.** Examples:
  - `text-primary-400`, NOT `text-primary` or `text-orange-500`.
  - `bg-surface-400`, NOT `bg-surface` or `bg-slate-800`.
  - `bg-background-400`, NOT `bg-background`.
  - `text-neutral-heading`, NOT `text-slate-950`.
- These tokens automatically support light and dark themes. Do not add redundant `dark:` color overrides when the existing token already handles both.
- Check `theme.css` for the correct available token instead of inventing a new color or token name.
- **Define gradients in CSS inside `@pushOnce('styles')`, never with Tailwind gradient utility classes.**
- Reuse existing fonts, spacing tokens, breakpoints, and utility classes.

## Layout and Figma fidelity

- **Do not add horizontal padding, width constraints, or centering utilities to page sections unless explicitly present in the Figma design.**
- **Never add `max-w-*`, `mx-auto`, or extra `px-*` to sections or their outer wrappers just to constrain or center content.**
- Page width, horizontal spacing, and alignment are already handled externally by the grid in `base.css`.
- The grid uses `.content-grid`, `.full-width`, `.breakout`, and `.fill-width`. Respect their existing behavior instead of recreating container layouts.
- Do not introduce unnecessary wrapper elements or nested containers that interfere with the grid.
- Match Figma spacing exactly. Only add padding or width constraints when explicitly required by the design or component's internal layout.
- Internal component spacing (e.g. card padding, button padding, grid gaps) is allowed when required by the design.

## JavaScript

- Use existing vanilla JavaScript modules or Alpine.js.
- Do not introduce Vue, React, or Inertia.

## Docker, build, and tests

Run Docker commands from the repository root:

```bash
docker compose up --build -d
docker compose logs -f app vite db
docker compose exec vite npm run build
docker compose exec -e APP_ENV=testing app php artisan test
```

- Compose exposes Laravel on port 8000, Vite on 5173, and MariaDB. Vite starts its dev server automatically.
- Tests use PHPUnit/Pest and live in `app/tests/`.
- **Always run tests with `docker compose exec -e APP_ENV=testing app php artisan test`**, optionally passing a test path or filter.
- Add or update relevant tests for every code change, even when not explicitly requested.
- Run the narrowest relevant tests and build after application changes.

## Working practices

- Inspect nearby code before editing. Reuse established architecture, components, helpers, validation, authorization, and tests.
- Keep changes focused. Avoid modifying migrations, dependencies, or environment configuration unless necessary.
- Never generate images.
