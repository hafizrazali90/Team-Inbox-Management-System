#!/bin/bash

echo "================================================"
echo "  TIMS Backend Server Startup"
echo "================================================"
echo ""

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ ERROR: PHP is not installed!"
    echo ""
    echo "Please install PHP first:"
    echo "  - Restart your Codespace, or"
    echo "  - Run: ./setup-codespaces.sh"
    echo "  - Or see START_SERVERS.md for manual installation"
    echo ""
    exit 1
fi

echo "✓ PHP version: $(php --version | head -1)"
echo ""

cd "$(dirname "$0")/backend"

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "📦 Installing Composer dependencies..."
    composer install
    echo ""
fi

# Check if .env exists
if [ ! -f ".env" ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo ""
fi

# Check if APP_KEY is set
if grep -q "APP_KEY=base64:cGxlYXNlX2dlbmVyYXRlX3RoaXNfd2l0aF9hcnRpc2Fu" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate
    echo ""
fi

# Check if database exists
if [ ! -f "database/database.sqlite" ]; then
    echo "💾 Creating SQLite database..."
    touch database/database.sqlite
    echo ""
fi

# Check if migrations have been run
if ! php artisan migrate:status &> /dev/null; then
    echo "📊 Running database migrations..."
    php artisan migrate --seed
    echo ""
fi

echo "================================================"
echo "  Starting Laravel development server..."
echo "================================================"
echo ""
echo "📍 Backend API will be available at:"
echo "   http://localhost:8000"
echo ""
echo "📡 API endpoints:"
echo "   http://localhost:8000/api/*"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""
echo "================================================"
echo ""

# Start the server
php artisan serve --host=0.0.0.0
