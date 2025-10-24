#!/bin/bash

# TIMS Quick Setup Script
# This script will set up the backend with SQLite for easy testing

set -e

echo "========================================"
echo "TIMS Quick Setup for Local Development"
echo "========================================"
echo ""

cd backend

# Step 1: Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "✅ .env created"
else
    echo "✅ .env already exists"
fi

# Step 2: Configure for SQLite
echo ""
echo "🔧 Configuring database for SQLite..."
sed -i.bak 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
sed -i.bak 's/^BROADCAST_DRIVER=.*/BROADCAST_DRIVER=log/' .env
echo "✅ Database configured"

# Step 3: Generate APP_KEY if not set
if ! grep -q "^APP_KEY=base64:" .env; then
    echo ""
    echo "🔑 Generating application key..."
    php artisan key:generate
    echo "✅ Application key generated"
else
    echo ""
    echo "✅ Application key already set"
fi

# Step 4: Create SQLite database
echo ""
echo "💾 Creating SQLite database..."
touch database/database.sqlite
echo "✅ Database file created"

# Step 5: Run migrations
echo ""
echo "🔄 Running database migrations..."
php artisan migrate --force
echo "✅ Migrations completed"

# Step 6: Seed database
echo ""
echo "🌱 Seeding database with sample data..."
php artisan db:seed --force
echo "✅ Database seeded"

# Step 7: Clear caches
echo ""
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
echo "✅ Caches cleared"

# Step 8: Show user count
echo ""
echo "👥 Checking users..."
USER_COUNT=$(php artisan tinker --execute="echo count(\App\Models\User::all());" 2>/dev/null || echo "0")
echo "✅ Found $USER_COUNT users in database"

echo ""
echo "========================================"
echo "✅ Backend Setup Complete!"
echo "========================================"
echo ""
echo "🎯 Login Credentials:"
echo ""
echo "Admin:"
echo "  Email: admin@tims.local"
echo "  Password: password123"
echo ""
echo "Manager:"
echo "  Email: sarah.manager@tims.local"
echo "  Password: password123"
echo ""
echo "CX Agents:"
echo "  Email: alice.wong@tims.local"
echo "  Password: password123"
echo ""
echo "========================================"
echo "🚀 Next Steps:"
echo "========================================"
echo ""
echo "1. Start backend server:"
echo "   cd backend"
echo "   php artisan serve"
echo ""
echo "2. In a new terminal, start frontend:"
echo "   cd frontend"
echo "   npm install"
echo "   npm run dev"
echo ""
echo "3. Open browser:"
echo "   http://localhost:3000"
echo ""
echo "4. Login with credentials above"
echo ""
echo "========================================"
echo ""
echo "📝 Database: backend/database/database.sqlite"
echo "📋 Logs: backend/storage/logs/laravel.log"
echo "📚 Full guide: SETUP_GUIDE.md"
echo ""
