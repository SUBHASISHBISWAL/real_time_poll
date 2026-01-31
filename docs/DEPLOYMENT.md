# Deployment Guide (Free Tier)

This guide covers two methods to deploy your Laravel application for free:
1.  **InfinityFree** (Best for "Forever Free" shared hosting, no credit card)
2.  **Render.com** (Best for modern Git-based deployment, easier setup)

---

## Option 1: InfinityFree (Shared Hosting)
**Pros:** Truly free, no time limits, cPanel interface.
**Cons:** No SSH (Terminal), Manual file upload, slower.

### Step 1: Prepare your Application
Since InfinityFree doesn't support SSH (Terminal), you must prepare your files locally.
1.  Run `composer install --optimize-autoloader --no-dev` locally.
2.  Run `npm run build` to compile assets.
3.  Delete the `node_modules` folder (not needed on server).
4.  Zip everything in your project folder **EXCEPT**:
    *   `.git` folder
    *   `.env` (you will create this manually)

### Step 2: Create Account & Database
1.  Sign up at [InfinityFree](https://infinityfree.com/).
2.  Create a "Hosting Account".
3.  Go to the **Control Panel** (VistaPanel).
4.  Click **"MySQL Databases"**.
5.  Create a new database (e.g., `poll_app`).
6.  **Note down** the:
    *   MySQL Hostname (usually `sqlXXX.infinityfree.com`)
    *   MySQL Username (e.g., `if0_35...`)
    *   MySQL Password (found in Client Area)
    *   Database Name

### Step 3: Upload Files
1.  Open the **"Online File Manager"** from the Client Area.
2.  Navigate to `htdocs`.
3.  **Delete** the empty `index2.html` or `default` files.
4.  **Upload** your project Zip file and **Extract** it.
    *   *Note:* It is best to put your Laravel files in a folder named `core` outside `htdocs` for security, and only put the contents of `public` inside `htdocs`.
    *   **Simpler Method (Less Secure):** Extract everything into `htdocs`.

### Step 4: Configure Entry Point
If you uploaded everything to `htdocs`:
1.  Rename `server.php` to `index.php`.
2.  Create an `.htaccess` file in `htdocs` with:
    ```apache
    <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteRule ^(.*)$ public/$1 [L]
    </IfModule>
    ```

### Step 5: Database Import
1.  Export your local database:
    *   Use a tool like **HeidiSQL** or run: `mysqldump -u root -p real_time_poll > backup.sql`
2.  In InfinityFree Control Panel, go to **"phpMyAdmin"**.
3.  Select your database.
4.  Click **"Import"** and upload your `backup.sql`.

### Step 6: Connect Environment
1.  In File Manager, find `.env.example`.
2.  Rename it to `.env`.
3.  Edit it with your InfinityFree DB credentials:
    ```ini
    APP_ENV=production
    APP_DEBUG=false
    APP_URL=http://your-site.infinityfreeapp.com

    DB_CONNECTION=mysql
    DB_HOST=sqlXXX.infinityfree.com
    DB_PORT=3306
    DB_DATABASE=if0_35..._poll_app
    DB_USERNAME=if0_35...
    DB_PASSWORD=your_vpanel_password
    ```

---

## Option 2: Render (Modern Cloud)
**Pros:** Automated Git deployment, SSL included, Modern.
**Cons:** Free database expires in 90 days.

### Step 1: Push to GitHub
Ensure your code is committed and pushed to a GitHub repository.

### Step 2: Create Web Service
1.  Sign up at [Render.com](https://render.com).
2.  Click **"New +"** -> **"Web Service"**.
3.  Connect your GitHub repository.
4.  **Settings**:
    *   **Name**: `poll-platform`
    *   **Region**: Nearest to you
    *   **Runtime**: PHP
    *   **Build Command**: `composer install --no-dev --optimize-autoloader`
    *   **Start Command**: `php artisan serve --host 0.0.0.0 --port $PORT`
5.  **Environment Variables**:
    *   Add `APP_KEY` (copy from your local .env)
    *   Add `APP_ENV` = `production`

### Step 3: Create Database
1.  Dashboard -> **"New +"** -> **"PostgreSQL"** (Laravel supports pgsql out of the box, easier on Render).
2.  Copy the `Internal Database URL`.
3.  Go back to your **Web Service** -> **Environment**.
4.  Add `DATABASE_URL` = (paste the internal URL).
5.  In your Laravel `config/database.php`, change default to `pgsql` OR update environment variables to map:
    *   `DB_CONNECTION` = `pgsql`
    *   `DB_HOST` , `DB_PORT`, etc. from the Render details.

### Step 4: Migration
In the Render Shell (or as a Build Command script), run:
`php artisan migrate --force`

---

## Recommendation
**Use InfinityFree** if you want it to stay online forever for free and don't mind manual file uploads.
**Use Render** if you want a quick, professional demo linked to GitHub, but accept the database might need resetting after 90 days.
