# CivicLink – Banking and Home Affairs Service Portal

A modular PHP/MySQL demonstration application for XAMPP. It supports customer accounts,
identity checks, passport applications, and Home Affairs applications for birth, marriage,
death and national ID replacement services.

## Requirements
- XAMPP (Apache and MySQL)
- PHP 8.0+ (PDO and sessions enabled)
- MySQL/MariaDB

## Installation
1. Copy the `civiclink` folder into `C:\xampp\htdocs\`.
2. Start Apache and MySQL in the XAMPP Control Panel.
3. Open phpMyAdmin at `http://localhost/phpmyadmin`.
4. Import `database/civiclink.sql`.
5. Check `config/database.php` and update the database username/password if necessary.
6. Visit `http://localhost/civiclink/public/`.
7. Create an administrator using the one-time setup page:
   `http://localhost/civiclink/public/admin-setup.php`
8. Delete `public/admin-setup.php` after creating the administrator.

## Default local database settings
- Database: `civiclink`
- Username: `root`
- Password: empty (the XAMPP default; change this for your installation)

## Home Affairs services
- Passport: new application, renewal and replacement
- Birth certificate application
- Marriage certificate application
- Death certificate application
- National ID replacement

Each application receives a unique reference number. Customers can view their applications
and track status. Administrators can review applications, add remarks and change status.

## Banking and identity features
- Register and view customer profiles
- Create and view demonstration bank accounts
- Configurable identity verification mode: automatic demo verification or manual review
- Admin management of application statuses and system settings

## Important integration note
This is a functional local prototype. Bank, identity and Home Affairs actions are stored in
the local database. It does not connect to Eswatini government or bank production systems.
Real verification, payments, document issuance and status exchange require formal permission,
documented APIs, credentials and security review from the relevant institutions.

## Security notes
Change database credentials, use HTTPS in deployment, remove the admin setup page after use,
and review the application before exposing it to the internet. Uploaded documents and payments
are intentionally not implemented in this prototype.

\n## Service category navigation
The three home-page service cards are generated from `config/service_catalog.php`.
Edit that catalogue to change category titles, descriptions, service labels or destinations.
`public/services.php` renders each category from the same configuration. Identity and Banking
links open the matching sections in `public/profile.php`; Home Affairs links open the application
form with the chosen service pre-selected.
