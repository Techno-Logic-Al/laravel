# admin<[station]

A neon‑styled Laravel admin panel for managing companies and employees. Built as a portfolio piece with a distinctive dark techy UI, smooth transitions, and a polished CRUD experience.

## Overview

admin<[station] is a single‑user administrative dashboard that lets you:

- Log in as an admin and manage companies and employees
- Upload logos and avatars
- Browse and search data with sorting, pagination, and “recently added” summaries
- Switch between a full neon theme and a monochrome dark mode

The app ships with realistic demo data (companies, employees, and icons/avatars) so it looks “alive” as soon as it’s set up.

## Features

- **Authentication**
  - Login‑only flow (registration disabled)
  - Admin home redirects to a custom dashboard

- **Companies**
  - Full CRUD (create, edit, view, delete)
  - Logo upload (min 100×100 pixels) stored on the `public` disk
  - Company show page with:
    - Name, email, website, logo
    - Created/updated timestamps
    - Employee count
    - Embedded employees table for that company

- **Employees**
  - Full CRUD (create, edit, view, delete)
  - Required gender field (Female/Male)
  - Optional avatar upload (min 100×100) stored on the `public` disk
  - Placeholder avatar when no image is uploaded
  - Employee show page with:
    - Full name, ID, email, phone, gender
    - Linked company panel (square logo, name, email, website)

- **Dashboard**
  - “Dashboard” card showing:
    - Logged‑in user name
    - Total employees and total companies
  - “Recently Added” cards:
    - 5 most recent companies (logo + created date)
    - 5 most recent employees (avatar + created date)
  - Buttons for “View All” and “Add” actions

- **Tables & UX**
  - Companies and employees index tables with:
    - Pagination
    - Clickable rows (go to show page)
    - Column sorting (name, company, email, website, counts, last updated, etc.)
  - Navbar search:
    - Expanding search icon
    - Live results dropdown (companies + employees) after 3 characters

- **Styling & Effects**
  - Dark neon theme: animated navbar and card headers (blue → orange → pink)
  - Glassy, semi‑transparent cards and tables over an animated background
  - Glowing primary/danger buttons
  - Custom rounded employee avatars with gradient backgrounds
  - Square company icons with white background and glow
  - Page transition overlay with “wow” sweep‑in/out animation
  - **Theme toggle:** neon vs. mono dark mode (black/white/grey) via navbar button

## Tech Stack

- **Backend**
  - PHP 8.4+ (developed on 8.5)
  - Laravel 12.x
  - SQLite database

- **Frontend**
  - Blade templates
  - Bootstrap as a base, heavily customized with SCSS
  - SCSS (`resources/sass/app.scss`) compiled with Vite
  - Vanilla JS (`resources/js/app.js`) for search, transitions, and theme toggle

## Requirements

- PHP **8.4 or higher**
- Composer
- Node.js + npm
- SQLite extension enabled in PHP

## Getting Started (Local)

1. **Clone the repository**

   ```bash
   git clone <your-repo-url> admin-station
   cd admin-station
   ```

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Install frontend dependencies**

   ```bash
   npm install
   ```

4. **Environment setup**

   - Copy `.env.example` to `.env`:

     ```bash
     cp .env.example .env
     ```

   - Set the basics in `.env`:

     ```env
     APP_NAME="admin<[station]"
     APP_ENV=local
     APP_DEBUG=true
     APP_URL=http://localhost:8000

     DB_CONNECTION=sqlite
     # Leave DB_DATABASE empty to use database/database.sqlite by default
     ```

5. **Database**

   - Create the SQLite file:

     ```bash
     mkdir -p database
     touch database/database.sqlite
     ```

   - Run migrations:

     ```bash
     php artisan migrate
     ```

6. **Admin user & demo data**

   There are seeders and factories to populate:

   - An admin user (e.g. `admin@admin.com`)
   - Companies from `storage/app/public/seed-icons/PNG`
   - Employees from `storage/app/public/seed-avatars/{female,male}`

   Typical usage:

   ```bash
   php artisan db:seed --class=AdminUserSeeder
   php artisan db:seed --class=CompanyFromIconsSeeder
   php artisan db:seed --class=EmployeeFromAvatarsSeeder
   ```

   Make sure the seed icon/avatar files are present under `storage/app/public/` before seeding.

7. **Storage symlink**

   To expose uploaded logos/avatars via `/storage`:

   ```bash
   php artisan storage:link
   ```

8. **Build or watch assets**

   For development:

   ```bash
   npm run dev
   ```

   For a production build:

   ```bash
   npm run build
   ```

9. **Run the app**

   ```bash
   php artisan serve
   ```

   Visit [http://localhost:8000](http://localhost:8000) and log in with the seeded admin credentials.

## Deployment Notes (SQLite + Shared Hosting)

The app is configured to use SQLite by default:

```php
// config/database.php
'default' => env('DB_CONNECTION', 'sqlite'),
'database' => env('DB_DATABASE', database_path('database.sqlite')),
```

On a shared host (e.g. cPanel):

- Upload a prepared `database/database.sqlite`.
- Set in `.env` on the server:

  ```env
  APP_ENV=production
  APP_DEBUG=false
  DB_CONNECTION=sqlite
  ```

- Point the domain/subdomain’s **document root** to the `public` folder of this app.
- Recreate the `public/storage` link, or copy the contents of `storage/app/public` into `public/storage` if symlinks aren’t allowed.
- Ensure PHP is set to **8.4+** for that vhost/subdomain.

## License

This project is for educational and portfolio purposes. No specific license has been declared.

