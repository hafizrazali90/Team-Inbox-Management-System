# TIMS Setup Guide for Local Development

## Current Status
You're trying to login but the backend is not properly configured yet.

## Prerequisites Needed

### 1. Backend Requirements
- **PHP 8.1+** with extensions: `mbstring`, `xml`, `curl`, `mysql`
- **Composer** (PHP package manager)
- **MySQL 8.0+** or **MariaDB 10.3+**

### 2. Frontend Requirements
- **Node.js 18+**
- **npm** or **yarn**

---

## Quick Setup (Step-by-Step)

### Step 1: Install Backend Dependencies

```bash
cd backend

# If composer is not installed, install it first:
# macOS: brew install composer
# Ubuntu/Debian: sudo apt install composer
# Windows: Download from https://getcomposer.org/

composer install
```

### Step 2: Configure Environment

The `.env` file has been created, but needs configuration:

```bash
# Generate application key (REQUIRED)
php artisan key:generate

# Your .env should now have APP_KEY set
```

### Step 3: Set Up Database

**Option A: Use SQLite (Easiest for testing)**

Edit `.env` and change:
```env
DB_CONNECTION=sqlite
# Comment out or remove MySQL settings
```

Then create the database file:
```bash
touch database/database.sqlite
```

**Option B: Use MySQL**

1. Start MySQL server
2. Create database:
```sql
CREATE DATABASE tims CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. Update `.env` with your MySQL credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tims
DB_USERNAME=your_mysql_username
DB_PASSWORD=your_mysql_password
```

### Step 4: Run Migrations and Seeders

```bash
# Run database migrations
php artisan migrate

# Seed database with sample data (includes default users)
php artisan db:seed
```

This will create:
- ✅ 5 roles (Admin, Operation Manager, Manager, AM, CX)
- ✅ 3 departments (CX active, TX/CR inactive)
- ✅ 7 sample users including admin
- ✅ 7 default tags
- ✅ 10 sample conversations with messages

### Step 5: Start Backend Server

```bash
# Start Laravel development server
php artisan serve

# Backend will run on http://localhost:8000
```

Keep this terminal open.

### Step 6: Set Up Frontend

Open a NEW terminal:

```bash
cd frontend

# Install dependencies
npm install

# Create .env file
cp .env.example .env
```

The frontend `.env` should have:
```env
VITE_API_URL=http://localhost:8000/api
VITE_WS_HOST=localhost
VITE_WS_PORT=6001
VITE_WS_KEY=local
VITE_APP_NAME=TIMS
```

### Step 7: Start Frontend Server

```bash
npm run dev

# Frontend will run on http://localhost:3000
```

### Step 8: Login to Application

Open browser: **http://localhost:3000**

**Default Admin Credentials:**
- Email: `admin@tims.local`
- Password: `password123`

**Other Test Users:**
- Manager: `sarah.manager@tims.local` / `password123`
- CX Agent: `alice.wong@tims.local` / `password123`
- CX Agent: `bob.chen@tims.local` / `password123`

---

## Troubleshooting Login Issues

### Issue 1: "Cannot connect to backend"

**Check:**
```bash
# Is backend running?
curl http://localhost:8000/api/auth/login
# Should return: {"message":"The email field is required..."}
```

**Fix:**
- Make sure `php artisan serve` is running
- Check backend terminal for errors

### Issue 2: "Invalid credentials"

**Check database:**
```bash
cd backend
php artisan tinker

# In tinker console:
>>> \App\Models\User::where('email', 'admin@tims.local')->first()
# Should show user details

>>> exit
```

**If no user found:**
```bash
php artisan db:seed --class=UserSeeder
```

### Issue 3: "500 Internal Server Error"

**Check Laravel logs:**
```bash
cd backend
tail -f storage/logs/laravel.log
```

Common issues:
- Missing APP_KEY → Run `php artisan key:generate`
- Database connection error → Check `.env` database settings
- Missing storage permissions → Run `chmod -R 775 storage bootstrap/cache`

### Issue 4: Frontend shows CORS errors

**Check backend .env:**
```env
FRONTEND_URL=http://localhost:3000
```

**Clear config cache:**
```bash
cd backend
php artisan config:clear
php artisan cache:clear
```

---

## Quick Health Check Script

Run this to check your setup:

```bash
cd backend

echo "=== TIMS Health Check ==="
echo ""

# Check .env exists
[ -f .env ] && echo "✅ .env exists" || echo "❌ .env missing"

# Check APP_KEY
grep "^APP_KEY=base64:" .env > /dev/null && echo "✅ APP_KEY set" || echo "❌ APP_KEY not set - Run: php artisan key:generate"

# Check database connection
php artisan db:show > /dev/null 2>&1 && echo "✅ Database connected" || echo "❌ Database not connected"

# Check if users exist
php artisan tinker --execute="echo count(\App\Models\User::all()) . ' users in database';" 2>/dev/null || echo "❌ Cannot query users"

echo ""
echo "=== Next Steps ==="
echo "1. php artisan serve (in backend directory)"
echo "2. npm run dev (in frontend directory)"
echo "3. Open http://localhost:3000"
echo "4. Login with: admin@tims.local / password123"
```

---

## Common Commands Reference

### Backend
```bash
cd backend

# Start server
php artisan serve

# Clear cache
php artisan config:clear
php artisan cache:clear

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Check routes
php artisan route:list

# Interactive shell
php artisan tinker
```

### Frontend
```bash
cd frontend

# Start dev server
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview
```

---

## SQLite Quick Start (Recommended for Testing)

If you don't want to set up MySQL, use SQLite:

```bash
cd backend

# 1. Edit .env
cat > .env << 'EOF'
APP_NAME="TIMS"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

FRONTEND_URL=http://localhost:3000

SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1
SESSION_DOMAIN=localhost
EOF

# 2. Generate key
php artisan key:generate

# 3. Create SQLite database
touch database/database.sqlite

# 4. Run migrations and seeders
php artisan migrate --seed

# 5. Start server
php artisan serve
```

Now you can login! 🎉

---

## Need Help?

If you're still having issues, provide:
1. Error message from browser console (F12)
2. Error from `backend/storage/logs/laravel.log`
3. Output of: `cd backend && php artisan --version`
