# Micro Banking System

## Project Overview

This project is a micro-banking system built using Laravel that enables users to view daily account movements and balances over a selected date range.

## Requirements

- PHP >= 8.0
- Laravel Herd / Docker
- Composer
- MySQL or SQLite

## Installation

1. Clone the repository:

    ```git url
    https://github.com/obakengmanana/micro-banking-system.git
    ```

    ```bash
    gh repo clone obakengmanana/micro-banking-system
    cd micro-banking-system
    ```

2. Install dependencies:

    ```bash
    composer install
    npm install && npm run dev
    ```

3. Set up environment variables:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Migrate and seed the database:

    ```bash
    php artisan migrate --seed
    ```

## Usage

1. Start the Laravel server:

    ```bash
    php artisan serve
    ```

2. Log in with the default credentials provided in the `.env` file or register a new account.

3. View the daily movement summary for any account and change the date range as needed.

## Testing

Run tests to ensure everything works as expected:

```bash
php artisan test
