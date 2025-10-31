# Project Overview

This project is an admin panel for the YSP_SPF application. It is built with Laravel 12 and serves as a frontend to an external API. The panel manages employees (`karyawan`), schedules (`jadwal`), attendance (`absensi`), and work patterns (`pola`).

The application uses a mix of server-rendered Blade templates and frontend assets managed by Vite.

**Key Technologies:**

*   **Backend:** PHP 8.2, Laravel 12
*   **Frontend:** JavaScript, TailwindCSS, Vite
*   **API Communication:** `axios` (via Laravel's `Http` facade)

This admin panel does not interact with a database directly. Instead, it makes authenticated requests to an API running at `http://127.0.0.1:8000/api`.

# Building and Running

1.  **Install Dependencies:**
    ```bash
    composer install
    npm install
    ```

2.  **Setup Environment:**
    Create the `.env` file and generate an application key.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Note: You may need to configure the `APP_URL` and other variables in the `.env` file, but most importantly, ensure the API URL is correctly configured if it differs from the default.*

3.  **Run the Development Servers:**
    This command will start the Laravel development server, the Vite development server, and a queue worker concurrently.
    ```bash
    composer run dev
    ```
    Alternatively, you can run them in separate terminals:
    *   `php artisan serve` (for the Laravel app)
    *   `npm run dev` (for frontend assets)

4.  **Run Tests:**
    Execute the test suite using Pest.
    ```bash
    php artisan test
    ```

# Development Conventions

*   **Routing:** Web routes are defined in `routes/web.php`. All admin routes are grouped and protected by the `admin` middleware.
*   **Controllers:** Controllers in `app/Http/Controllers` handle incoming web requests. Controllers like `AdminKaryawanController` are responsible for communicating with the backend API. They retrieve data from the API and pass it to the Blade views.
*   **Views:** Frontend templates are written using Blade and are located in `resources/views`. They use TailwindCSS for styling.
*   **API Interaction:** The application communicates with a separate backend API for all data operations. The base URL for the API is defined in the controllers (e.g., `private $apiBase = 'http://127.0.0.1:8000/api';` in `AdminKaryawanController`). Authentication is handled via a token passed in the `Authorization` header.
*   **Coding Style:** The project uses `laravel/pint` for code styling. You can run `./vendor/bin/pint` to format the code.
