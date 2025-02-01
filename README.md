# Laravel API Project Setup Guide

This guide will walk you through the steps to set up and run the Laravel API project on your local machine.

## Prerequisites

Before you begin, ensure you have the following installed on your machine:

-   **PHP** (version 8.0 or higher)
-   **Composer** (Dependency Manager for PHP)
-   **MySQL** or any other database supported by Laravel
-   **Git** (optional, for version control)

---

## Step 1: Clone the Repository

Clone the repository to your local machine:

```bash
git clone git@github.com:karlebh/ATS.git
cd your-repo-name
```

---

## Step 2: Install PHP Dependencies

Laravel uses Composer to manage its dependencies. Run the following command to install all the required PHP packages:

```bash
composer install
```

---

## Step 3: Configure Environment Variables

Laravel uses a `.env` file to manage environment-specific configuration. Copy the `.env.example` file to `.env` and update the necessary values:

```bash
cp .env.example .env
```

Open the `.env` file and configure the database connection settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

---

## Step 4: Generate Application Key

Laravel requires an application key for encryption. Generate it using the following command:

```bash
php artisan key:generate
```

---

## Step 5: Run Migrations

Migrations are used to create and modify database tables. Run the migrations to set up the database schema:

```bash
php artisan migrate
```

---

## Step 6: Seed the Database (Optional)

If your project includes seeders to populate the database with initial data, run the seeders:

```bash
php artisan db:seed
```

You can also run migrations and seeders together using:

```bash
php artisan migrate --seed
```

---

## Step 7: Serve the API

To serve the Laravel API locally, use the following command:

```bash
php artisan serve
```

By default, the API will be available at `http://127.0.0.1:8000`.

---

## Step 8: Test the API

You can test the API endpoints using tools like [Postman](https://www.postman.com/) or [cURL](https://curl.se/). Here are some example requests:

## Additional Commands

-   **Clear Cache**: If you encounter any issues, you might need to clear the cache:

    ```bash
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    ```

-   **Run Tests**: If your project includes tests, you can run them using:

    ```bash
    php artisan test
    ```

---

## Conclusion

You have successfully set up and run the Laravel API project on your local machine. If you encounter any issues, please refer to the Laravel documentation or open an issue in the repository.
