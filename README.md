# သောင်းပြောင်းထွေရာ

သောင်းပြောင်းထွေရာ is a Laravel web application for publishing and browsing article and video posts. This document describes the implementation present in this repository.

## Technology

- PHP 8.1 or newer and Laravel 10
- MySQL-compatible or another database supported by the configured Laravel connection
- Blade views, Laravel Breeze authentication, and Laravel Sanctum
- Bootstrap 5 (loaded from a CDN), Vite, Tailwind CSS, Alpine.js, and Axios

## Implemented functionality

- Public feed of published posts, search by post content or author name, article and video listings with category filters, and post detail pages
- Post view tracking, with a database uniqueness constraint limiting a post to one view per IP address per day
- A popular posts sidebar
- Post images and video references; post validation and file-storage behavior are implemented in the post controllers and requests
- Authenticated users can like, comment on, save, and share published posts; saved posts have a dedicated page
- Public comment listing and authenticated comment submission; the interaction schema has a parent relationship, while the current routes and UI expose top-level comments
- User profile and Laravel Breeze registration, login, password reset, and password update flows
- Admin dashboard, post management, and category management behind authentication and the `isAdmin` middleware
- Categories are classified as video or article and have an ordering value; the category navigation is used by the public sections
- Public storage link configuration, with an application route that can serve files from `storage/app/public` when needed

## Requirements

- PHP 8.1+ with the extensions required by Laravel 10
- Composer
- Node.js and npm
- A database supported by the Laravel connection configured in `.env`

## Installation

Clone the repository and install the PHP and JavaScript dependencies:

```sh
git clone https://github.com/kozinminhtet/thaung-pyaung-htwe-ya.git
cd thaung-pyaung-htwe-ya
composer install
npm install
```

Create a local environment file and generate an application key. In PowerShell:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Set `APP_URL` and the `DB_*` values in `.env` for your local application and database. Keep this file private; it is excluded by `.gitignore`. Configure mail and other services in `.env` only if you use those services.

Run the migrations and seed the configured database:

```sh
php artisan migrate --seed
```

The seeders create an admin account and the default article/video categories. The admin seeder currently assigns a fixed starter password in code; change that password before using the account outside a local development environment. Do not publish credentials in this README or commit local environment files.

Create the public storage link, then start Laravel and Vite in separate terminals:

```sh
php artisan storage:link
php artisan serve
```

```sh
npm run dev
```

Open the local URL reported by `php artisan serve`.

## Database and storage

Migrations create users, categories, posts, interactions, views, password reset tokens, failed jobs, and personal access tokens. A later additive migration adds `type` and `sort_order` to categories; its `down()` intentionally leaves those columns in place. `DatabaseSeeder` runs the admin and category seeders. Seeding the admin is not repeat-safe if the same email already exists, so inspect the target database before running seeders again.

The configured public disk stores uploaded public files under `storage/app/public`; `php artisan storage:link` maps that directory to `public/storage`. The route `/storage/{path}` is also defined as a fallback. Do not place private files in this public disk.

## Development commands

```sh
php artisan serve
npm run dev
npm run build
php artisan route:list
php artisan optimize:clear
```

The repository includes PHPUnit example and authentication/profile tests. Run them with `vendor/bin/phpunit` after installing dependencies and configuring a test database.

## Current limitations

- The interaction database supports parent comments, but the current request handlers return and create only top-level comments; threaded replies are not exposed.
- Sharing records the selected platform and increments the share count. The server does not publish content to a social network.
- Video posts use stored video URLs/provider values; this repository does not include a transcoding or media-hosting service.
- Admin account seeding uses a fixed starter password in source and is intended to be changed before deployment.
- Email verification routes and controllers are present, but the `User` model does not implement Laravel's email verification contract, so verification is not enabled for accounts.
- The checked-in tests are Laravel scaffold/example and authentication/profile tests; dedicated coverage for the post, category, and interaction flows is not present.
