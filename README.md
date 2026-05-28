# Thaung Pyaung Htwe Ya

Thaung Pyaung Htwe Ya is a Laravel content sharing web app. It includes a public feed, video and article sections, a profile page, and an admin area for managing posts.

## Main Features

- Public home feed for published posts
- Separate video and article pages
- Post detail page with view tracking
- Admin dashboard
- Admin post management with create, edit, publish, archive, and delete actions
- Image uploads for posts
- YouTube, Vimeo, and local video file support
- Popular posts sidebar
- Desktop layout with scrollable main feed and right sidebar
- Laravel Breeze authentication

## Tech Stack

- Laravel 10
- PHP 8.1+
- MySQL or another Laravel-supported database
- Blade templates
- Bootstrap 5
- Tailwind CSS and Vite
- Laravel Breeze authentication

## Setup

1. Clone the repository.

```bash
git clone https://github.com/kozinminhtet/thaung-pyaung-htwe-ya.git
cd thaung-pyaung-htwe-ya
```

2. Install PHP and JavaScript dependencies.

```bash
composer install
npm install
```

3. Create the environment file and application key.

```bash
cp .env.example .env
php artisan key:generate
```

On Windows PowerShell, use this instead of `cp`:

```powershell
Copy-Item .env.example .env
```

4. Configure the database in `.env`.

```env
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

5. Run migrations and seed sample data.

```bash
php artisan migrate --seed
```

6. Create the public storage link for uploaded images.

```bash
php artisan storage:link
```

7. Start the app.

```bash
php artisan serve
npm run dev
```

Open the URL shown by `php artisan serve`, usually `http://127.0.0.1:8000`.

## Admin Login

After running the seeder, you can log in with:

- Email: `admin@gmail.com`
- Password: `password`

Admin pages are available under `/admin`.

## Important Routes

- `/` - public feed
- `/video` - video posts
- `/articles` - article posts
- `/posts/{id}` - post detail page
- `/profile` - user profile
- `/admin/dashboard` - admin dashboard
- `/admin/posts` - admin post management

## Media Notes

Post images are stored on the public disk under `storage/app/public/posts`. The app serves them through `/storage/...`, so `php artisan storage:link` should be run after setup.

If the server cannot follow the storage symlink, the app also includes a Laravel fallback route for `/storage/{path}`.

## Development Notes

- Use `php artisan optimize:clear` after changing routes, config, or cached views.
- Use `npm run dev` while editing frontend assets.
- Use `npm run build` before deploying compiled frontend assets.
- Uploaded images should be JPG, PNG, or WebP.

## Author

Developed by Ko Zin Min Htet.
