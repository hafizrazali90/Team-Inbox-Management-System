# TIMS Setup for GitHub Codespaces

## 🚨 Important: Your Codespaces needs PHP installed

Your GitHub Codespaces environment currently has Node.js but **not PHP**. TIMS backend requires PHP 8.1+ to run Laravel.

---

## ✅ Quick Fix: Use the Pre-configured Codespace

The easiest way is to configure your Codespace with PHP. Here are your options:

### **Option 1: Install PHP in Current Codespace (Recommended)**

Run these commands in your Codespaces terminal:

```bash
# Install PHP 8.2 and required extensions
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-mbstring php8.2-xml php8.2-curl php8.2-sqlite3 php8.2-zip

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Verify installation
php --version
composer --version
```

After installation, continue with setup:

```bash
cd /workspaces/Team-Inbox-Management-System/backend

# Install Laravel dependencies
composer install

# Setup environment
cp .env.example .env
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env

# Generate app key
php artisan key:generate

# Create database
touch database/database.sqlite

# Run migrations
php artisan migrate --seed

# Start server
php artisan serve --host=0.0.0.0
```

---

### **Option 2: Create Dev Container Configuration**

Create `.devcontainer/devcontainer.json`:

```json
{
  "name": "TIMS Development",
  "image": "mcr.microsoft.com/devcontainers/php:8.2",
  "features": {
    "ghcr.io/devcontainers/features/node:1": {
      "version": "18"
    }
  },
  "postCreateCommand": "composer install && cd ../frontend && npm install",
  "forwardPorts": [8000, 3000],
  "customizations": {
    "vscode": {
      "extensions": [
        "bmewburn.vscode-intelephense-client",
        "dbaeumer.vscode-eslint"
      ]
    }
  }
}
```

Then rebuild your Codespace (Cmd/Ctrl + Shift + P → "Rebuild Container")

---

### **Option 3: Use Docker Compose (Alternative)**

If you prefer Docker:

```bash
# Create docker-compose.yml in project root
cat > docker-compose.yml << 'EOF'
version: '3.8'
services:
  backend:
    image: php:8.2-cli
    working_dir: /var/www
    volumes:
      - ./backend:/var/www
    ports:
      - "8000:8000"
    command: php artisan serve --host=0.0.0.0

  frontend:
    image: node:18
    working_dir: /app
    volumes:
      - ./frontend:/app
    ports:
      - "3000:3000"
    command: npm run dev
EOF

# Start services
docker-compose up
```

---

## 🎯 After PHP is Installed

Once you have PHP installed, here's the complete setup:

### **Backend Setup:**

```bash
cd backend

# Install dependencies
composer install

# Configure environment
cp .env.example .env

# Set SQLite (no MySQL needed)
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env

# Generate application key
php artisan key:generate

# Create database
touch database/database.sqlite

# Run migrations and create sample users
php artisan migrate --seed

# Start backend server
php artisan serve --host=0.0.0.0
```

### **Frontend Setup (New Terminal):**

```bash
cd frontend

# Install dependencies
npm install

# Start frontend
npm run dev
```

---

## 🌐 Accessing in Codespaces

When you start the servers, Codespaces will show notifications about port forwarding:

1. **Backend (Port 8000)** - Click "Open in Browser" or go to PORTS tab
2. **Frontend (Port 3000)** - Click "Open in Browser" or go to PORTS tab

---

## 🔑 Login Credentials

Once everything is running:

**Admin:**
- Email: `admin@tims.local`
- Password: `password123`

**CX Agents:**
- `alice.wong@tims.local` / `password123`
- `bob.chen@tims.local` / `password123`
- `carol.tan@tims.local` / `password123`

---

## 🐛 Troubleshooting

### "PHP command not found"
You need to install PHP first (see Option 1 above)

### "Composer command not found"
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Port already in use
```bash
# Kill existing process
sudo lsof -ti:8000 | xargs kill -9
```

### Database locked error
```bash
rm database/database.sqlite
touch database/database.sqlite
php artisan migrate:fresh --seed
```

---

## 📝 Quick Command Reference

```bash
# Backend
cd backend
composer install           # Install dependencies
php artisan migrate --seed  # Setup database
php artisan serve          # Start server

# Frontend
cd frontend
npm install               # Install dependencies
npm run dev              # Start dev server

# Database
php artisan migrate:fresh --seed  # Reset database
php artisan db:seed              # Re-seed only
php artisan tinker               # Interactive shell
```

---

## ✨ Next Steps

1. **Install PHP** (Option 1 recommended)
2. Run backend setup commands
3. Run frontend setup commands
4. Access your Codespace's forwarded ports
5. Login and start using TIMS!

---

**Need help?** Check the main [SETUP_GUIDE.md](./SETUP_GUIDE.md) for more details.
