#!/bin/bash

echo "================================================"
echo "  TIMS File Verification Report"
echo "================================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

check_file() {
    if [ -f "$1" ]; then
        echo -e "${GREEN}✓${NC} $1"
        return 0
    else
        echo -e "${RED}✗${NC} $1 (missing)"
        return 1
    fi
}

check_dir() {
    if [ -d "$1" ]; then
        echo -e "${GREEN}✓${NC} $1/"
        return 0
    else
        echo -e "${RED}✗${NC} $1/ (missing)"
        return 1
    fi
}

check_executable() {
    if [ -x "$1" ]; then
        echo -e "${GREEN}✓${NC} $1 (executable)"
        return 0
    else
        echo -e "${YELLOW}⚠${NC} $1 (not executable)"
        return 1
    fi
}

total=0
passed=0

echo "Backend Configuration Files:"
echo "----------------------------"
for file in app auth broadcasting cache cors database filesystems logging mail queue sanctum services session; do
    ((total++))
    check_file "backend/config/$file.php" && ((passed++))
done
echo ""

echo "Backend Core Files:"
echo "-------------------"
files=("backend/.env" "backend/.env.example" "backend/artisan" "backend/bootstrap/app.php" "backend/composer.json")
for file in "${files[@]}"; do
    ((total++))
    check_file "$file" && ((passed++))
done
echo ""

echo "Backend Routes:"
echo "---------------"
for route in api channels console web; do
    ((total++))
    check_file "backend/routes/$route.php" && ((passed++))
done
echo ""

echo "Backend Events:"
echo "---------------"
((total++))
check_file "backend/app/Events/MessageSent.php" && ((passed++))
((total++))
check_file "backend/app/Events/MessageReceived.php" && ((passed++))
echo ""

echo "Backend Directories:"
echo "--------------------"
((total++))
check_dir "backend/database/migrations" && ((passed++))
((total++))
check_dir "backend/app" && ((passed++))
((total++))
check_dir "backend/app/Http/Controllers" && ((passed++))
echo ""

echo "Frontend Files:"
echo "---------------"
files=("frontend/.env" "frontend/.env.example" "frontend/package.json" "frontend/vite.config.js" "frontend/src/App.jsx" "frontend/src/main.jsx")
for file in "${files[@]}"; do
    ((total++))
    check_file "$file" && ((passed++))
done
echo ""

echo "Frontend Directories:"
echo "---------------------"
((total++))
check_dir "frontend/src" && ((passed++))
((total++))
check_dir "frontend/src/components" && ((passed++))
((total++))
check_dir "frontend/node_modules" && ((passed++))
echo ""

echo "Scripts:"
echo "--------"
((total++))
check_executable "./start-frontend.sh" && ((passed++))
((total++))
check_executable "./start-backend.sh" && ((passed++))
((total++))
check_executable "./setup-codespaces.sh" && ((passed++))
((total++))
check_executable "./quick-setup.sh" && ((passed++))
echo ""

echo "Documentation:"
echo "--------------"
((total++))
check_file "START_SERVERS.md" && ((passed++))
((total++))
check_file "SETUP_STATUS.md" && ((passed++))
((total++))
check_file "SETUP_GUIDE.md" && ((passed++))
((total++))
check_file "WEBSOCKET_FIX_SUMMARY.md" && ((passed++))
((total++))
check_file "CODESPACES_SETUP.md" && ((passed++))
echo ""

echo "================================================"
echo "  System Requirements Check"
echo "================================================"
echo ""

# Check Node.js
if command -v node &> /dev/null; then
    echo -e "${GREEN}✓${NC} Node.js: $(node --version)"
else
    echo -e "${RED}✗${NC} Node.js: Not installed"
fi

# Check npm
if command -v npm &> /dev/null; then
    echo -e "${GREEN}✓${NC} npm: $(npm --version)"
else
    echo -e "${RED}✗${NC} npm: Not installed"
fi

# Check PHP
if command -v php &> /dev/null; then
    echo -e "${GREEN}✓${NC} PHP: $(php --version | head -1)"
else
    echo -e "${RED}✗${NC} PHP: Not installed (REQUIRED for backend)"
fi

# Check Composer
if command -v composer &> /dev/null; then
    echo -e "${GREEN}✓${NC} Composer: $(composer --version | head -1)"
else
    echo -e "${RED}✗${NC} Composer: Not installed (REQUIRED for backend)"
fi

echo ""
echo "================================================"
echo "  Summary"
echo "================================================"
echo ""
echo "Files checked: $passed/$total passed"
echo ""

if [ ! -command -v php &> /dev/null ]; then
    echo -e "${YELLOW}⚠  WARNING: PHP is not installed${NC}"
    echo "   Backend cannot start until PHP is installed."
    echo "   See START_SERVERS.md for installation instructions."
    echo ""
fi

if [ -d "frontend/node_modules" ] && [ -f "frontend/.env" ]; then
    echo -e "${GREEN}✓ Frontend is READY to start!${NC}"
    echo "  Run: ./start-frontend.sh"
    echo ""
else
    echo -e "${YELLOW}⚠ Frontend needs setup${NC}"
    echo "  Run: cd frontend && npm install"
    echo ""
fi

if command -v php &> /dev/null && [ -f "backend/.env" ] && [ -d "backend/vendor" ]; then
    echo -e "${GREEN}✓ Backend is READY to start!${NC}"
    echo "  Run: ./start-backend.sh"
    echo ""
elif command -v php &> /dev/null; then
    echo -e "${YELLOW}⚠ Backend needs setup${NC}"
    echo "  Run: cd backend && composer install"
    echo ""
else
    echo -e "${RED}✗ Backend cannot start - PHP not installed${NC}"
    echo "  See START_SERVERS.md for PHP installation"
    echo ""
fi

echo "================================================"
