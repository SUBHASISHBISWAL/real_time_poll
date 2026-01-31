# Module 1 Setup Guide

## Prerequisites
- PHP 8.0 or higher
- MySQL database
- Composer (for installing Laravel dependencies)

## Installation Steps

### 1. Install Composer Dependencies
Since Composer is not globally installed, download and install it first:
```bash
# Download Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# Install dependencies
php composer.phar install
```

### 2. Configure Database
Edit the `.env` file and update these values:
```
DB_DATABASE=poll_platform
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Create Database
```sql
CREATE DATABASE poll_platform;
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Seed Sample Data
```bash
php artisan db:seed
```

This creates:
- Admin user: admin@poll.com / password
- Regular user: user@poll.com / password
- 3 sample polls with options

### 7. Start Development Server
```bash
php artisan serve
```

Visit: http://localhost:8000

## Testing Module 1

1. **Register/Login**: Go to /register or /login
2. **View Polls**: After login, you'll see active polls (AJAX loaded)
3. **Click Poll**: Click any poll card to view options (no page reload)
4. **Navigation**: Use "Back to List" button to return (no page reload)

## Module 1 Completion Checklist
- [x] Database migrations created
- [x] User authentication (login/register)
- [x] Poll listing via AJAX
- [x] Poll detail view via AJAX
- [x] No page reloads for navigation
- [x] No hardcoded content
- [x] Bootstrap UI
- [x] Sample data seeder

## Next Steps
Module 2 will add:
- IP-restricted voting functionality
- Vote submission via AJAX
- Duplicate vote prevention
