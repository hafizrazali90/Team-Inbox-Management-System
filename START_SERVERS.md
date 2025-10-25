# How to Start Frontend and Backend Servers

## Current Status

✅ **Frontend: READY TO START** - Node.js is installed, dependencies are installed
❌ **Backend: BLOCKED** - PHP installation is blocked by network/DNS issues

---

## Option 1: Start Frontend Only (Works Now)

The frontend can be started immediately. It will show the UI but API calls will fail until backend is running.

### Start Frontend:

```bash
cd /home/user/Team-Inbox-Management-System/frontend
npm run dev
```

The frontend will start on **http://localhost:5173**

**Note:** The login page will load, but login will fail because the backend API is not running.

---

## Option 2: Fix PHP Installation Issue

### The Problem

Your Codespaces environment has a **DNS resolution issue** that prevents installing PHP via apt-get:
- Ubuntu package repositories cannot be reached
- `apt-get update` hangs indefinitely
- This is a GitHub Codespaces infrastructure issue

### Solution A: Restart Codespaces (Recommended)

1. **Stop this Codespace:**
   - Go to https://github.com/codespaces
   - Find your Codespace for this repository
   - Click "..." menu → "Stop codespace"

2. **Start it again:**
   - Click "Start codespace"
   - Wait for it to fully load

3. **Run the setup script:**
   ```bash
   cd /home/user/Team-Inbox-Management-System
   ./setup-codespaces.sh
   ```

4. **If successful, start both servers:**
   ```bash
   # Terminal 1: Backend
   cd backend
   php artisan serve --host=0.0.0.0

   # Terminal 2: Frontend
   cd frontend
   npm run dev
   ```

### Solution B: Use Local Development (Alternative)

If Codespaces continues to have issues:

1. **Clone the repository on your local machine:**
   ```bash
   git clone https://github.com/hafizrazali90/Team-Inbox-Management-System.git
   cd Team-Inbox-Management-System
   git checkout claude/tims-project-scaffold-011CUSUPj7MaNBijVBcUR9Yg
   ```

2. **Ensure you have installed:**
   - PHP 8.2+ (`php --version`)
   - Composer (`composer --version`)
   - Node.js 18+ (`node --version`)

3. **Run quick setup:**
   ```bash
   chmod +x quick-setup.sh
   ./quick-setup.sh
   ```

4. **Start both servers:**
   ```bash
   # Terminal 1: Backend
   cd backend
   php artisan serve

   # Terminal 2: Frontend
   cd frontend
   npm run dev
   ```

### Solution C: Manual PHP Installation (Advanced)

If you want to try installing PHP manually in Codespaces:

1. **Try updating DNS and repositories:**
   ```bash
   # Add alternative DNS
   echo -e "nameserver 1.1.1.1\nnameserver 1.0.0.1" | sudo tee /etc/resolv.conf

   # Try update again
   sudo apt-get update
   ```

2. **If that works, install PHP:**
   ```bash
   sudo apt-get install -y php8.2 php8.2-cli php8.2-mbstring php8.2-xml \
       php8.2-curl php8.2-sqlite3 php8.2-zip php8.2-bcmath
   ```

3. **Install Composer:**
   ```bash
   curl -sS https://getcomposer.org/installer | sudo php -- \
       --install-dir=/usr/local/bin --filename=composer
   ```

4. **Continue with setup:**
   ```bash
   cd backend
   composer install
   php artisan key:generate
   touch database/database.sqlite
   php artisan migrate --seed
   php artisan serve --host=0.0.0.0
   ```

---

## Verification Checklist

### ✅ Files Ready (All Complete):

**Backend Configuration:**
- ✅ `backend/config/app.php`
- ✅ `backend/config/auth.php`
- ✅ `backend/config/broadcasting.php`
- ✅ `backend/config/cache.php`
- ✅ `backend/config/cors.php`
- ✅ `backend/config/database.php`
- ✅ `backend/config/filesystems.php`
- ✅ `backend/config/logging.php`
- ✅ `backend/config/mail.php`
- ✅ `backend/config/queue.php`
- ✅ `backend/config/sanctum.php`
- ✅ `backend/config/session.php`

**Backend Core:**
- ✅ `backend/.env` (created from .env.example)
- ✅ `backend/.env.example`
- ✅ `backend/artisan` (executable)
- ✅ `backend/bootstrap/app.php` (Laravel 10 format)
- ✅ `backend/composer.json`
- ✅ `backend/routes/api.php`
- ✅ `backend/routes/channels.php`
- ✅ `backend/routes/console.php`
- ✅ `backend/routes/web.php`
- ✅ `backend/app/Events/MessageSent.php`
- ✅ `backend/app/Events/MessageReceived.php`
- ✅ `backend/database/migrations/` (14 migration files)

**Frontend:**
- ✅ `frontend/.env` (created from .env.example)
- ✅ `frontend/.env.example`
- ✅ `frontend/package.json`
- ✅ `frontend/vite.config.js`
- ✅ `frontend/node_modules/` (dependencies installed)
- ✅ `frontend/src/App.jsx`
- ✅ `frontend/src/main.jsx`
- ✅ `frontend/src/components/` (all components)

### ⏳ Pending (Requires PHP):

- ⏳ PHP 8.2 installation
- ⏳ Composer installation
- ⏳ `backend/vendor/` (Composer dependencies)
- ⏳ `backend/database/database.sqlite` (needs creation)
- ⏳ Database migrations (needs to run)
- ⏳ Database seeding (test users)

---

## Test Login Credentials

Once both servers are running, test with these credentials:

**Admin User:**
- Email: `admin@tims.local`
- Password: `password123`

**Manager User:**
- Email: `sarah.manager@tims.local`
- Password: `password123`

**CX Agent:**
- Email: `alice.wong@tims.local`
- Password: `password123`

---

## Expected Behavior

### When Both Servers Are Running:

1. **Frontend** runs on http://localhost:5173 (or Codespaces URL)
2. **Backend API** runs on http://localhost:8000
3. **Login page** loads successfully
4. **Login with credentials** works
5. **Dashboard** displays after login
6. **Real-time messaging** works (via broadcasting)

### When Only Frontend Is Running:

1. **Frontend** runs on http://localhost:5173
2. **Login page** loads successfully
3. **Login fails** with "Network Error" or "Cannot reach API"
4. Browser console shows: `Failed to fetch` or `ERR_CONNECTION_REFUSED`

---

## Troubleshooting

### If Frontend Won't Start:

```bash
cd frontend
rm -rf node_modules package-lock.json
npm install
npm run dev
```

### If Backend Won't Start (After PHP is Installed):

```bash
cd backend
composer install
php artisan config:clear
php artisan cache:clear
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan serve --host=0.0.0.0
```

### Check What's Running:

```bash
# Check if backend is running
curl http://localhost:8000/api/health 2>&1

# Check if frontend dev server is running
curl http://localhost:5173 2>&1

# Check Node.js processes
ps aux | grep node

# Check PHP processes (if installed)
ps aux | grep php
```

---

## Network Issue Details

**Why can't we install PHP?**

Your Codespaces environment cannot resolve Ubuntu package repository domains:
- `archive.ubuntu.com` - DNS resolution fails
- `security.ubuntu.com` - DNS resolution fails
- `ppa.launchpadcontent.net` - DNS resolution fails

**What works:**
- ✅ GitHub access (we pushed code successfully)
- ✅ npm installs from npmjs.com
- ✅ curl to google.com
- ❌ apt-get update (hangs/times out)
- ❌ Ubuntu package installation

**Root cause:** This is a GitHub Codespaces infrastructure issue, not related to:
- Your GitHub 2FA settings
- Your repository permissions
- Your code or configuration

---

## Next Steps

1. **Try restarting your Codespace** (Solution A above)
2. **Or switch to local development** (Solution B above)
3. **Once PHP is installed, run:**
   ```bash
   cd /home/user/Team-Inbox-Management-System
   ./setup-codespaces.sh
   ```
4. **Then start both servers as shown above**

All your code files are ready and properly configured. The only blocker is PHP installation!
