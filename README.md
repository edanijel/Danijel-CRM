# Danijel CRM

A simple CRM system built with Laravel (Livewire/Volt stack). Created as a project assignment to build a CRM application supporting companies, contacts, deals, and offers.


## Tech stack

- Laravel 12 + Livewire 3 (Volt single-file components)
- Alpine.js + Tailwind CSS
- SQLite (local) / MySQL (production)
- barryvdh/laravel-dompdf — offer PDF export
- SortableJS — drag & drop on Kanban boards


## Running locally

Prerequisites: PHP 8.2+, Composer, Node.js 18+ and npm.

1. Clone the repository:
   ```
   git clone https://github.com/edanijel/Danijel-CRM.git
   cd Danijel-CRM
   ```

2. Install dependencies:
   ```
   composer install
   npm install
   ```

3. Create the `.env` file and generate the application key:
   ```
   cp .env.example .env
   php artisan key:generate
   ```

4. Create the SQLite database and run migrations (the database is already configured in `.env.example`, no separate DB server needed):
   ```
   touch database/database.sqlite
   php artisan migrate
   ```

5. Build frontend assets:
   ```
   npm run build
   ```

6. Start the local server and run the application (If using Laravel Herd, the project is automatically available at `http://danijel-crm.test` without this step.)
   ```
   php artisan serve
   ```

7. Open `http://127.0.0.1:8000`, click **Register**, and create a user account.

## Features

- User login/registration
- CRUD interfaces for Companies, Contacts, Deals, and Offers
- Kanban board per entity, grouped by status, with drag & drop
- Shortcut to quickly create an Offer directly from the Deal Kanban board of Deal form
- Offer export to PDF