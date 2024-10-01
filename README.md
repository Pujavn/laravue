# Vue 3 with Laravel 8 Project

## Introduction
This is a full-stack web application built using **Vue.js 3** for the front-end and **Laravel 8** for the back-end. It combines the power of Vue for a reactive and modern user interface and Laravel's robust back-end capabilities for handling business logic, authentication, and API handling.

## Technologies Used
- **Vue.js 3**: For building reactive and modular front-end components.
- **Laravel 8**: For providing a powerful back-end, handling routing, models, and database interactions.
- **MySQL** (or any other database): Used as the database engine (you can specify your own).
- **API Routes**: Built-in with Laravel to handle API requests for the Vue front-end.

## Project Setup

### Prerequisites
- **PHP** >= 7.3 (for Laravel)
- **Composer** (to manage Laravel dependencies)
- **Node.js** >= 12 (for Vue and Laravel Mix)
- **npm** (Node package manager)

### Step 1: Clone the repository
To get a copy of the project locally, clone this repository:
```bash
git clone https://github.com/your-username/your-repo-name.git


cd your-repo-name
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
npm run dev

For production, you can compile the front-end assets by running:
npm run build



============================
Commands used
2 npm list vue
   3 npm list vue-router
   4 composer require laravel/sanctum
   5 php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   6 php artisan migrate
   7 
  26 php artisan migrate:status
  27 php artisan make:seeder UserSeeder
  28 php artisan db:seed
  29 php artisan config:clear
  30 php artisan cache:clear
  37 php artisan make:migration add_fields_to_users_table
  38 php artisan make:migration create_states_table
  39 php artisan make:migration create_cities_table
  40 php artisan make:migration create_user_state_table
  41 php artisan make:migration create_user_city_table
  42 php artisan make:model State
  43 php artisan make:model City
  44 php artisan make:controller StateController
  45 php artisan make:controller CityController

# Mailhog with Laravel (Using Docker)

## Introduction

Mailhog is an email testing tool that allows you to capture outgoing emails and view them through a web interface. It’s perfect for development environments where you don’t want to send actual emails but still need to verify that your email functionality is working. This guide will show you how to use Mailhog with Docker in a Laravel project.

## Installation Using Docker

### Step 1: Run Mailhog via Docker

To start Mailhog using Docker, run the following command:

```bash
docker run -d -p 1025:1025 -p 8025:8025 mailhog/mailhog