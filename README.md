




<!-- for Onlineshp Admin Dashboard -->
# Onlineshop

Laravel based Online Shopping System with Admin Panel.

## Features

- User Registration & Login
- Product Management
- Order Management (Pending, Processing, Completed, Cancelled)
- Order Status Notification (Email + Database)
- New User Registration Notification (to Admin & Superadmin)
- Admin / Superadmin Role System
- User Active/Disable Status
- Profile Management

## Tech Stack

- Laravel 13
- MySQL
- Bootstrap 5.3.8
- Font Awesome

## Installation
npm install sweetalert2 //
import './bootstrap'; //

// SweetAlert2 //
import Swal from 'sweetalert2'; //
window.Swal = Swal;   // global use

//resources/css/app.css //
@import 'sweetalert2/dist/sweetalert2.min.css';

//resources/js/app.js //
import 'sweetalert2/dist/sweetalert2.min.css';

1. Clone the repository
```bash
git clone https://github.com/aungkyawthetakt052-ship-it/laravel-onlineshop-admindashboard.git


2.Install dependencies

composer install
npm install && npm run build

3.Environment setup

 cp .env.example .env
php artisan key:generate

4.Configure database in .env

envDB_DATABASE=onlineshop
DB_USERNAME=root
DB_PASSWORD=

5.Run migrations

php artisan migrate

6.Create storage link

php artisan storage:link

7.Start the server
php artisan serve

Queue Setup (for Notifications)
Notifications are queued for better performance.

php artisan queue:table
php artisan migrate
php artisan queue:work
