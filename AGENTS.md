# Project Instructions

## Stack and architecture

- Laravel 12, PHP 8.2+ (Docker: 8.4), Livewire 3, Filament 4, Tailwind CSS 4, and Vite.
- Laravel root: `app/`. Run Artisan, Composer, and npm commands there; Docker commands run from the repository root.
- Follow existing architecture. Controllers handle HTTP, models handle persistence/domain behavior, and `app/app/Services/` contains reusable business logic. Keep business logic out of Blade.
- Routes: `app/routes/`, with middleware assigned by `App\Providers\RouteServiceProvider`.
- Ecommerce models: `App\Models\Ecommerce`; Livewire: `app/app/Livewire/`; Filament: `app/app/Filament/`; Livewire views: `app/resources/views/livewire/`.
- Follow existing validation, authorization, Livewire events, and component patterns.
- Check legacy integration and Composer autoload mappings before changing legacy code.

## Agent efficiency and scope

- **Minimize unnecessary tool calls, file reads, output, and changes without sacrificing correctness.**
- Use targeted searches (`rg`, `rg --files`). Read relevant files only; expand investigation when necessary.
- Avoid repeated file reads, commands, repository-wide searches, and unrelated audits.
- Implement straightforward changes directly. Plan briefly only for complex tasks.
- Make the smallest change that fully addresses the request. Do not refactor unrelated code.
- Preserve existing APIs, routes, events, component props, and database schemas unless requested otherwise.
- Prefer existing solutions over new abstractions. Avoid speculative fallbacks, defensive code, compatibility layers, and premature optimization.
- Remove obsolete code only after verifying it is unused.
- Do not modify dependencies, migrations, environment configuration, generated files, or build output unless necessary.
- Never generate images or unsolicited documentation.

## Blade components and helpers

- Reuse existing Blade components before writing new markup, especially layouts, forms, buttons, typography, cards, tables, and pagination.
- Anonymous components: `app/resources/views/components/`; class-backed: `app/app/View/Components/`.
- Prefer `<x-input>`, `<x-select>`, `<x-textarea>`, and `<x-button>` when their APIs support the requirement over HTML elements.
- Use `@pushOnce('styles')` and `@pushOnce('scripts')` for component-specific assets when needed.
- Reuse helpers in `app/app/Helpers/` and global shorthands loaded by `AppServiceProvider` (e.g. `user()`, `cart()`, `price()`).
- Avoid duplicating styling or behavior already provided by components.

## Styling and design tokens

- Tailwind v4 tokens: `app/resources/css/theme.css`. Shared layout utilities: `base.css`. Both are imported by `app.css`.
- **ALWAYS use semantic color tokens defined in `theme.css`. NEVER use Tailwind's default color palette, hardcoded colors, or invented color tokens.**
- **Use exact token names, including numeric suffixes:**
  - `text-primary-400`, NOT `text-primary` or `text-orange-500`.
  - `bg-surface-400`, NOT `bg-surface` or `bg-slate-800`.
  - `bg-background-400`, NOT `bg-background`.
  - `text-heading-400`, NOT `text-slate-950`.
- Check `theme.css` for available tokens. They already support light/dark themes; avoid redundant `dark:` color overrides.

## Layout and Figma fidelity

- **NEVER add horizontal padding, width constraints, or centering utilities to page sections unless explicitly required by the Figma design.**
- **Do NOT add `max-w-*`, `mx-auto`, or extra `px-*` to sections or their outer wrappers merely to constrain or center content.**
- Respect `.content-grid`, `.full-width`, `.breakout`, and `.fill-width`. Do not recreate their behavior with nested containers or unnecessary wrappers.

## JavaScript

- Use existing vanilla JavaScript modules or Alpine.js.
- Do not introduce Vue, React, or Inertia.

## Docker and validation

Run Docker commands from the repository root:

```bash
# Start only if needed
docker compose up -d

# Inspect recent logs
docker compose logs --tail=100 app vite db

# Run targeted PHP tests
docker compose exec -e APP_ENV=testing app php artisan test --filter=TestName

# Run full suite when justified
docker compose exec -e APP_ENV=testing app php artisan test

# Build frontend assets
docker compose exec vite npm run build
```

- Assume containers are already running. Do not restart, rebuild, or install dependencies unnecessarily.
- Laravel runs on port 8000; Vite on 5173. Vite starts automatically.
- Tests use Pest in `app/tests/` or Playwright in app/e2e. Always use `APP_ENV=testing` for PHP tests.
- Add or update tests for new behavior, bug fixes, or meaningful logic changes when existing coverage is insufficient.
- Styling, markup-only, and documentation changes do not require new tests.
- Run frontend builds only when relevant to modified assets or compilation behavior.
- Avoid repeated validation unless changes or failures justify it.
- Never run destructive database commands without explicit approval.
- Report validation failures or checks that could not be performed.

## Browser and visual testing

- Minimize browser interactions and screenshot analysis
  to reduce AI credit consumption.
- Prefer inspecting HTML, CSS, and component code first.
- Use browser screenshots only when visual verification
  is necessary.

## Communication

- Be concise, technical, and action-oriented.
- Skip introductory explanations and detailed plans for straightforward tasks.
- Do not explain basic Laravel, PHP, Blade, or Tailwind concepts unless requested.
- Do not repeat code already visible in modified files.
- After changes, briefly summarize what changed, validation performed, and any remaining issues.
- Resolve minor ambiguities using existing conventions. Ask only when missing information materially affects correctness.
