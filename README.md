# Laravel PackageApp Wizard Installer – Setup Guide
 
This guide will walk you through the **complete installation and setup process** for the PackageApp Wizard Installer, from cloning the repository to launching your Laravel application with dynamic package selection.
 
---
 
## Table of Contents
 
1. [Requirements](#requirements)
2. [Clone the Repository](#clone-the-repository)
3. [Composer Install](#composer-install)
4. [Environment Setup](#environment-setup)
5. [Database Setup](#database-setup)
6. [Wizard Installer Flow](#wizard-installer-flow)
    - [Step 1: Industry Selection](#step-1-industry-selection)
    - [Step 2: Database Configuration](#step-2-database-configuration)
    - [Step 3: Package Selection & Installation](#step-3-package-selection--installation)
    - [Step 4: Admin Credentials](#step-4-admin-credentials)
    - [Step 5: Finalization](#step-5-finalization)
7. [Running Migrations](#running-migrations)
8. [Accessing the Application](#accessing-the-application)
9. [Troubleshooting](#troubleshooting)
10. [Customizing Packages](#customizing-packages)
 
---
 
## Requirements
 
- PHP 8.2 or higher
- Composer
- MySQL or compatible database
- Node.js & npm (for frontend assets, if needed)
- Laravel 12
 
---
<!--
## Clone the Repository
 
```bash
git clone <your-repo-url> packageapp_new
cd packageapp_new
```
 
--- -->
 
## Composer Install
 
Install all PHP dependencies:
 
```bash
composer install
```
 
---
 
## Environment Setup
 
1. Copy the example environment file:
 
    ```bash
    cp .env.example .env
    ```
 
2. Generate the application key:
 
    ```bash
    php artisan key:generate
    ```
 
3. Edit `.env` and set your database credentials and other environment variables.
 
---
 
## Database Setup
 
- **The wizard create it for you during the installation flow.**
 
---
 
## Wizard Installer Flow
 
Start your local server:
 
```bash
php artisan serve
```
 
Visit [http://127.0.0.1:8000/wizard-install](http://127.0.0.1:8000/wizard-install) to launch the wizard.
 
### Step 1: Industry Selection
 
- Select your industry from the dropdown.
- Click **Next**.
 
### Step 2: Database Configuration
 
- Enter your website name, database name, username, and password.
- The wizard will attempt to create the database and user.
- Click **Next**.
 
### Step 3: Package Selection & Installation
 
- Select one or more packages to install.
- Click **Next**.
- The wizard will:
    - Show a loader and status for each package.    
    - Check if each package is installed (by checking the `vendor` directory).
    - Show "In Process" and then "Installed" for each package.
 
### Step 4: Admin Credentials
 
- Enter the admin email and password.
- Click **Submit**.
- The wizard will:  
    - Create the admin user.
    - Update the `.env` file with the new database credentials.
    - Clear and cache Laravel config.
 
### Step 5: Finalization
 
- You will see a success message and be redirected to the thank you page.
 
---
 
## Running Migrations
 
If you need to run migrations manually:
 
```bash
php artisan migrate --force
```
 
---
 
## Accessing the Application
 
- After installation, you can access the admin panel at:  
  `http://HOST_NAME/WEBISTE_NAME/admin/login`  
  *(Replace `WEBISTE_NAME` with your actual website name and `HOST_NAME` with your actual host name)*
 
---
 
## Troubleshooting
 
- **500 Server Error:** Check `storage/logs/laravel.log` for details.
- **Composer errors:** Ensure `composer` is available in your system PATH and has write permissions.
- **Database errors:** Ensure your MySQL user has privileges to create databases and users.
- **Permissions:** Ensure `storage` and `bootstrap/cache` are writable.
 
---
 
## Notes
 
- The wizard uses AJAX for a smooth, step-by-step experience.
- All package installations and migrations are handled automatically.
- The `.env` file is updated with your chosen database credentials.
 
---
 
**Enjoy your modular Laravel application!**