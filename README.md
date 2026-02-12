------------Task Managemtn System---------

A small Task Management System built with full-stack Laravel.
It demonstrates the core functionalities of Laravel including:
Task assignment by admin to users
Notifications on task assignment
Alerts for due date updates using Laravel queues
User authentication & role-based access



Run following commands

Install PHP dependencies:
1) composer install
Install Node.js dependencies:
2) npm install
Generate the application key:
3) php artisan key:generate
Run database migrations and seeders:
4) php artisan migrate --seed
Start Laravel development server:
php artisan serve
Start frontend:
npm run dev
--
Make sure Node.js and NPM are installed for frontend dependencies.
Ensure queue worker is running for scheduled notifications:
php artisan queue:work

The project will be accessible at:
http://127.0.0.1:8000

Default Accounts:

1) Admin Email & password

Email: admin@example.com
password: password123

2) User Email & password

Email: user@example.com
password: password123

Features:
Admin panel for task assignment
User dashboard to view assigned tasks
Real-time notifications for task assignment
Alerts for task due dates
Queue-based backend notifications
Role-based access (Admin/User)

Tech Stack:
Backend: Laravel 11
Frontend: Blade Template
Database: MySQL
Notifications: Laravel Queues 


