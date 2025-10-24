#!/bin/bash

# TIMS Complete Setup Script for Codespaces
# Run this in your Codespaces terminal

set -e

echo "========================================="
echo "TIMS Complete Setup for Codespaces"
echo "========================================="
echo ""

# Check if we're in the right directory
if [ ! -d "backend" ] || [ ! -d "frontend" ]; then
    echo "❌ Error: Please run this from the project root directory"
    echo "   Run: cd /workspaces/Team-Inbox-Management-System"
    exit 1
fi

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "📦 Installing PHP 8.2 and extensions..."
    sudo apt update -qq
    sudo apt install -y php8.2 php8.2-cli php8.2-mbstring php8.2-xml php8.2-curl php8.2-sqlite3 php8.2-zip unzip
    echo "✅ PHP installed"
else
    echo "✅ PHP already installed: $(php --version | head -1)"
fi

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo "📦 Installing Composer..."
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
    echo "✅ Composer installed"
else
    echo "✅ Composer already installed: $(composer --version | head -1)"
fi

echo ""
echo "========================================="
echo "Setting up Backend..."
echo "========================================="
echo ""

cd backend

# Install Composer dependencies
if [ ! -d "vendor" ]; then
    echo "📦 Installing Composer dependencies..."
    composer install --quiet --no-interaction
    echo "✅ Dependencies installed"
else
    echo "✅ Vendor directory exists"
fi

# Setup .env
echo "🔧 Configuring environment..."
cat > .env << 'EOF'
APP_NAME="TIMS"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file

FRONTEND_URL=http://localhost:3000
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1
SESSION_DOMAIN=localhost
EOF

# Generate APP_KEY
echo "🔑 Generating application key..."
php artisan key:generate --force

# Create SQLite database
echo "💾 Creating database..."
touch database/database.sqlite

# Run migrations
echo "🔄 Running migrations..."
php artisan migrate --force

# Seed database
echo "🌱 Creating sample users..."
php artisan db:seed --force

# Clear cache
echo "🧹 Clearing cache..."
php artisan config:clear
php artisan cache:clear

# Verify users were created
USER_COUNT=$(php artisan tinker --execute="echo count(\App\Models\User::all());" 2>/dev/null || echo "0")
echo "✅ Database setup complete - $USER_COUNT users created"

echo ""
echo "========================================="
echo "Setting up Frontend..."
echo "========================================="
echo ""

cd ../frontend

# Create .env
if [ ! -f ".env" ]; then
    echo "🔧 Creating frontend .env..."
    cat > .env << 'EOF'
VITE_API_URL=http://localhost:8000/api
VITE_WS_HOST=localhost
VITE_WS_PORT=6001
VITE_WS_KEY=local
VITE_APP_NAME=TIMS
EOF
    echo "✅ Frontend .env created"
fi

# Install npm dependencies
if [ ! -d "node_modules" ]; then
    echo "📦 Installing npm dependencies..."
    npm install --silent
    echo "✅ npm dependencies installed"
else
    echo "✅ node_modules exists"
fi

cd ..

echo ""
echo "========================================="
echo "✅ Setup Complete!"
echo "========================================="
echo ""
echo "🎯 Login Credentials:"
echo "   Email: admin@tims.local"
echo "   Password: password123"
echo ""
echo "🚀 To start the application:"
echo ""
echo "Terminal 1 (Backend):"
echo "   cd backend"
echo "   php artisan serve --host=0.0.0.0"
echo ""
echo "Terminal 2 (Frontend):"
echo "   cd frontend"
echo "   npm run dev -- --host"
echo ""
echo "Then open the forwarded port for 3000 in Codespaces"
echo "========================================="
