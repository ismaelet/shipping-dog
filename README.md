# Shipping Dog

Shipping Dog is a Laravel-based e-commerce platform for discovering, managing, and selling creative designs and products. The application includes a storefront, account and seller experiences, and an administration interface.

## Technology stack

| Area | Technologies |
| --- | --- |
| Backend | PHP 8.4+, Laravel 13 |
| Admin interface | Filament 4 |
| Frontend | Blade, Livewire, Alpine.js, TypeScript/JavaScript |
| Styling and assets | Tailwind CSS 4, Vite 6 |
| Database | MariaDB / MySQL-compatible database |
| Development infrastructure | Docker Compose |
| Monitoring | Sentry |
| Testing | Pest 5, Vitest, Playwright |
| Quality tools | Laravel Pint, Oxfmt, Oxlint |

Additional dependencies include Laravel Socialite, SortableJS, and Dompdf.

## Application overview

The project covers several areas of an e-commerce experience:

- **Storefront:** product and design discovery, search, and product details.
- **Customer experience:** accounts, shopping cart, and order-related flows.
- **Seller tools:** seller dashboard, portfolio management, and design organization.
- **Administration:** application management through Filament.

Exact payment, shipping, and third-party integrations depend on the environment configuration.

## Repository structure

```text
shipping-dog/
├── app/                  # Laravel application
│   ├── app/              # Application code
│   ├── config/           # Laravel configuration
│   ├── database/         # Migrations, factories, seeders
│   ├── resources/        # Blade views, styles, scripts
│   ├── routes/           # Web, auth, dashboard, and service routes
│   ├── tests/            # Feature and unit tests
│   ├── composer.json     # PHP dependencies and scripts
│   └── package.json      # Frontend dependencies and scripts
├── docker/               # Docker-related configuration
├── compose.yml           # Development Compose configuration
├── compose.prod.yml      # Production Compose configuration
└── README.md
```

## Getting started

### Prerequisites

For containerized development, install Docker and Docker Compose. For running commands directly on your machine, you will need PHP 8.4+, Composer, Node.js, pnpm, and a compatible database.

The frontend package manager is **pnpm** (see `app/package.json`). Run Composer, Artisan, and frontend commands from the `app/` directory unless otherwise indicated.

### Local configuration

Create a local environment configuration using the example provided by the project, if available, and supply your own development credentials. Do not commit environment files or real credentials.

Generate a Laravel application key when initializing a new environment:

```bash
cd app
php artisan key:generate
```

Configure the database and any optional integrations before running migrations.

### Development with Docker

From the repository root:

```bash
docker compose up -d --build
```

Use the services and execution commands defined in `compose.yml` for dependencies, migrations, and application access. For example, if the application service is named `app`:

```bash
docker compose exec app php artisan migrate
```

### Development without Docker

From `app/`, install dependencies:

```bash
composer install
pnpm install
```

After configuring the local environment and database:

```bash
php artisan migrate
```

Start the backend and frontend development servers in separate terminals:

```bash
php artisan serve
```

```bash
pnpm dev
```

## Frontend and build commands

Run these from `app/`:

| Command | Purpose |
| --- | --- |
| `pnpm dev` | Start Vite development server |
| `pnpm build` | Build production frontend assets |
| `pnpm format` | Format supported frontend files with Oxfmt |
| `pnpm format:check` | Check formatting without modifying files |
| `pnpm lint` | Lint supported frontend files with Oxlint |

## Tests and code quality

Run Laravel tests from `app/`:

```bash
php artisan test
```

Run PHP formatting checks with Laravel Pint:

```bash
vendor/bin/pint --test
```

Vitest and Playwright are included as frontend testing dependencies. Use the project's configured test files and scripts to run the relevant suites.

## Architecture notes

Laravel handles routing, persistence, and server-side application logic. Blade, Livewire, and Alpine.js provide interactive views, while Filament powers administrative interfaces. Vite manages the frontend assets, and Docker Compose defines local and production service configurations.

The repository also contains legacy PHP namespaces in its Composer autoload configuration; these should be considered when modifying older parts of the application.

## Deployment

A production Compose definition is available in `compose.prod.yml`. Deployment requires environment-specific configuration, credentials, persistent storage, and infrastructure provisioning that are intentionally not included in this README.

Do not reuse development credentials in production. Consult the deployment configuration and your hosting environment before deploying.

## Security

- Never commit `.env` files, API keys, database dumps, or private certificates.
- Use environment variables or an appropriate secret-management mechanism for credentials.
- Review uploaded assets, logs, and backups before adding files to version control.
- If a credential was committed in the past, revoke or rotate it; rewriting Git history alone does not invalidate it.

## License

No public reuse license is declared in this README. Unless a license is explicitly granted, do not assume permission to redistribute or reuse the project.
