#!/bin/bash

echo "================================================"
echo "  TIMS Frontend Server Startup"
echo "================================================"
echo ""
echo "Starting React + Vite development server..."
echo ""
echo "📍 Frontend will be available at:"
echo "   http://localhost:5173"
echo ""
echo "⚠️  Note: Backend API must be running for login to work"
echo "   Backend should be at: http://localhost:8000"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""
echo "================================================"
echo ""

cd "$(dirname "$0")/frontend"

# Check if node_modules exists
if [ ! -d "node_modules" ]; then
    echo "📦 Installing dependencies first..."
    npm install
    echo ""
fi

# Start the dev server
npm run dev
