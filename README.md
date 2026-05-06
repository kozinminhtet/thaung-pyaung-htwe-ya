# Thaung Pyaung Htwe Ya

A Laravel-based web application for content sharing, including posts, videos, and articles.
This project simulates a simple social media platform where users can interact with content and admins can manage data.

---

## Project Status

This project is currently under development.

### Completed Features

* Database design and table creation
* Authentication system using Laravel Breeze
* Feed page (displaying posts)
* Videos page
* Articles page
* Basic frontend integration with sample (fake) data

### 🔄 In Progress

* User Profile (Account Profile page)

### Planned Features

* User interactions (like, comment, save, share)
* Admin panel (CRUD for posts, categories, comments)
* Real data integration (replacing fake data)
* UI/UX improvements

---

## Tech Stack

* Laravel (PHP Framework)
* MySQL (Database)
* Blade (Templating Engine)
* Tailwind CSS

---

## Installation

1. Clone the repository

```bash
git clone https://github.com/kozinminhtet/thaung-pyaung-htwe-ya.git
```

2. Navigate into the project

```bash
cd thaung-pyaung-htwe-ya
```

3. Install dependencies

```bash
composer install
npm install && npm run dev
```

4. Copy environment file

```bash
cp .env.example .env
```

5. Generate application key

```bash
php artisan key:generate
```

6. Run migrations

```bash
php artisan migrate
```

7. Start the development server

```bash
php artisan serve
```

---

## 📖 Notes

* This project is actively being developed and improved.
* Some features currently use sample (fake) data for testing purposes.

---

##Author

Developed by Ko Zin Min Htet
