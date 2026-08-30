




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

8.Queue Setup (for Notifications)
Notifications are queued for better performance.

php artisan queue:table
php artisan migrate
php artisan queue:work

9.Delete form

npm install sweetalert2 
