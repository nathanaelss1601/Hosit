#!/bin/bash

# Restaurant Management System - Installation Script
# Usage: bash install.sh

echo "================================================"
echo "Restaurant Management System - Installation"
echo "================================================"
echo ""

# Check PHP version
echo "Checking PHP version..."
php_version=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
echo "PHP Version: $php_version"

if (( $(echo "$php_version < 8.2" | bc -l) )); then
    echo "❌ Error: PHP 8.2 or higher is required"
    exit 1
fi

echo "✅ PHP version OK"
echo ""

# Check Composer
echo "Checking Composer..."
if ! command -v composer &> /dev/null; then
    echo "❌ Error: Composer not found. Please install Composer first."
    exit 1
fi

echo "✅ Composer found"
echo ""

# Install dependencies
echo "Installing dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

if [ $? -ne 0 ]; then
    echo "❌ Error: Failed to install dependencies"
    exit 1
fi

echo "✅ Dependencies installed"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    echo "✅ .env file created"
else
    echo "✅ .env file already exists"
fi

echo ""

# Generate application key
echo "Generating application key..."
php artisan key:generate --ansi

echo ""

# Create directories
echo "Creating storage directories..."
mkdir -p storage/app/public/products
mkdir -p storage/app/public/receipts
mkdir -p public/uploads

echo "✅ Directories created"
echo ""

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

if [ $? -ne 0 ]; then
    echo "❌ Error: Failed to run migrations"
    exit 1
fi

echo "✅ Migrations completed"
echo ""

# Run seeders
echo "Seeding database..."
php artisan db:seed --force

if [ $? -ne 0 ]; then
    echo "❌ Error: Failed to seed database"
    exit 1
fi

echo "✅ Database seeded"
echo ""

# Create storage link
echo "Creating storage link..."
php artisan storage:link

echo "✅ Storage link created"
echo ""

# Set permissions
echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo "✅ Permissions set"
echo ""

# Display success message
echo "================================================"
echo "✅ Installation Complete!"
echo "================================================"
echo ""
echo "Default Login Credentials:"
echo ""
echo "Admin:"
echo "  Email: admin@restaurant.com"
echo "  Password: admin123"
echo ""
echo "Kasir:"
echo "  Email: kasir@restaurant.com"
echo "  Password: kasir123"
echo ""
echo "To start the server, run:"
echo "  php artisan serve"
echo ""
echo "Then open: http://localhost:8000"
echo ""
echo "================================================"
echo "Happy coding! 🎉"
echo "================================================"
