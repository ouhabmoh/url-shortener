# URL Shortener Project

This project is a simple URL shortener built with Laravel and Vue.js.

## Table of Contents

-   [Requirements](#requirements)
-   [Installation](#installation)
-   [Configuration](#configuration)
-   [Usage](#usage)
-   [Endpoints](#endpoints)
-   [License](#license)

## Requirements

-   PHP and Laravel
-   Composer
-   Node.js and npm
-   MySQL

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/your-username/url-shortener.git
    ```

2. Install PHP dependencies:

    ```bash
    composer install
    ```

3. Install JavaScript dependencies:

    ```bash
    npm install
    ```

4. Create a copy of the `.env.example` file:

    ```bash
    cp .env.example .env
    ```

5. Generate the application key:

    ```bash
    php artisan key:generate
    ```

6. Configure your database in the `.env` file:

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=your-db-host
    DB_PORT=your-db-port
    DB_DATABASE=your-db-name
    DB_USERNAME=your-db-username
    DB_PASSWORD=your-db-password
    SAFE_BROWSING_API_KEY=your-api-key
    HASH_LENGTH=your-short-url-hash-length
    APP_URL=base-url-for-the-short-url
    ```

7. Migrate the database:

    ```bash
    php artisan migrate
    ```

8. Compile assets:

    ```bash
    npm run dev
    ```

## Configuration

-   The project uses the `config/app.php` file for various configuration options.
-   You can customize the URL format and length in the `ShortUrlController.php` file.

## Usage

-   Run the Laravel development server:

    ```bash
    php artisan serve
    ```

-   Visit [http://localhost:8000](http://localhost:8000) in your browser.

## Endpoints

-   `/`: Home page with the URL shortening form.
-   `/url`: POST: Create a short url from an origin url.
-   `/url`: GET: list of all urls with their hash from database.
-   `/{hash}`: Redirects to the original URL based on the provided hash.

## License

This project is open-source and available under the [MIT License](LICENSE).
