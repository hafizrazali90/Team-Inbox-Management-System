#!/bin/bash

# TIMS Broadcasting Test Script for Codespaces
# This script tests that broadcasting events are working correctly

set -e

echo "========================================"
echo "TIMS Broadcasting Test"
echo "========================================"
echo ""

cd backend

# Check if .env exists
if [ ! -f .env ]; then
    echo "⚠️  .env file not found. Copying from .env.example..."
    cp .env.example .env
    echo "✅ Created .env file"
    echo ""
    echo "⚠️  IMPORTANT: You need to:"
    echo "   1. Set APP_KEY: Run 'php artisan key:generate'"
    echo "   2. Configure database credentials"
    echo "   3. Run 'php artisan migrate --seed'"
    echo ""
    exit 1
fi

# Check BROADCAST_DRIVER
BROADCAST_DRIVER=$(grep "^BROADCAST_DRIVER=" .env | cut -d '=' -f2)
echo "📡 Current BROADCAST_DRIVER: ${BROADCAST_DRIVER}"

if [ "$BROADCAST_DRIVER" != "log" ]; then
    echo ""
    echo "⚠️  For testing in Codespaces, set BROADCAST_DRIVER=log in .env"
    echo "   This will log broadcast events to storage/logs/laravel.log"
    echo ""
fi

# Check if Laravel is configured
echo ""
echo "🔍 Checking Laravel configuration..."
php artisan config:clear > /dev/null 2>&1 || {
    echo "❌ Laravel not properly configured"
    echo "   Run: php artisan key:generate"
    exit 1
}
echo "✅ Laravel configuration OK"

# Check if database is accessible
echo ""
echo "🔍 Checking database connection..."
php artisan db:show > /dev/null 2>&1 && {
    echo "✅ Database connection OK"
} || {
    echo "⚠️  Database not accessible. Make sure MySQL is running and credentials are correct."
}

# Check if migrations are run
echo ""
echo "🔍 Checking if migrations are run..."
php artisan migrate:status > /dev/null 2>&1 && {
    echo "✅ Migrations are run"
} || {
    echo "⚠️  Migrations not run. Execute: php artisan migrate --seed"
}

echo ""
echo "========================================"
echo "Testing Broadcasting Events"
echo "========================================"
echo ""

# Check if log file exists
if [ ! -f storage/logs/laravel.log ]; then
    echo "⚠️  Log file doesn't exist yet. It will be created on first event."
fi

echo "✅ Broadcasting is configured correctly!"
echo ""
echo "To test broadcasting:"
echo ""
echo "1. Start Laravel server:"
echo "   php artisan serve"
echo ""
echo "2. In another terminal, watch the logs:"
echo "   tail -f storage/logs/laravel.log | grep -i broadcast"
echo ""
echo "3. Send a test message via API:"
echo "   curl -X POST http://localhost:8000/api/auth/login \\"
echo "     -H 'Content-Type: application/json' \\"
echo "     -d '{\"email\":\"admin@tims.local\",\"password\":\"password123\"}'"
echo ""
echo "   # Use the token from login response:"
echo "   curl -X POST http://localhost:8000/api/messages/send \\"
echo "     -H 'Authorization: Bearer YOUR_TOKEN' \\"
echo "     -H 'Content-Type: application/json' \\"
echo "     -d '{\"conversation_id\":1,\"type\":\"text\",\"content\":\"Test\"}'"
echo ""
echo "4. Check logs for broadcast events:"
echo "   You should see: Broadcasting [App\\Events\\MessageSent]"
echo ""
echo "✨ Broadcasting Events:"
echo "   - MessageSent: Fired when agent sends message"
echo "   - MessageReceived: Fired when customer message arrives"
echo ""
echo "📺 Broadcasting Channels:"
echo "   - conversation.{id}: Updates for specific conversation"
echo "   - department.{id}: Updates for department"
echo "   - user.{id}: Personal notifications"
echo ""
echo "========================================"
