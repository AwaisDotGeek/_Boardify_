#!/bin/bash

# Boardify Production Deployment Script
# This script automates the deployment process

set -e  # Exit on error

echo "========================================="
echo "Boardify Production Deployment"
echo "========================================="
echo ""

# Check if running as root
if [ "$EUID" -eq 0 ]; then
    echo "⚠️  Please do not run this script as root"
    exit 1
fi

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ Error: .env file not found!"
    echo "Please copy .env.example to .env and configure it first."
    exit 1
fi

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Error: Docker is not installed!"
    echo "Please install Docker first: https://docs.docker.com/get-docker/"
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Error: Docker Compose is not installed!"
    echo "Please install Docker Compose first: https://docs.docker.com/compose/install/"
    exit 1
fi

echo "✅ Prerequisites check passed"
echo ""

# Pull latest changes
echo "📥 Pulling latest changes from Git..."
git pull origin main
echo ""

# Build Docker images
echo "🔨 Building Docker images..."
docker-compose build
echo ""

# Start containers
echo "🚀 Starting Docker containers..."
docker-compose up -d
echo ""

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
sleep 10
echo ""

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
docker-compose exec -T app composer install --optimize-autoloader --no-dev
echo ""

# Install NPM dependencies
echo "📦 Installing NPM dependencies..."
docker-compose exec -T app npm ci
echo ""

# Build frontend assets
echo "🎨 Building frontend assets..."
docker-compose exec -T app npm run build
echo ""

# Run database migrations
echo "🗄️  Running database migrations..."
docker-compose exec -T app php artisan migrate --force
echo ""

# Clear and cache configurations
echo "🧹 Clearing old cache..."
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan route:clear
docker-compose exec -T app php artisan view:clear
docker-compose exec -T app php artisan cache:clear
echo ""

echo "💾 Caching configurations..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache
docker-compose exec -T app php artisan event:cache
echo ""

# Set proper permissions
echo "🔐 Setting proper permissions..."
docker-compose exec -T app chown -R www-data:www-data /var/www/html/storage
docker-compose exec -T app chown -R www-data:www-data /var/www/html/bootstrap/cache
docker-compose exec -T app chmod -R 775 /var/www/html/storage
docker-compose exec -T app chmod -R 775 /var/www/html/bootstrap/cache
echo ""

# Restart services
echo "🔄 Restarting application services..."
docker-compose restart app queue scheduler
echo ""

# Show running containers
echo "📊 Running containers:"
docker-compose ps
echo ""

# Check application health
echo "🏥 Checking application health..."
if docker-compose exec -T app php artisan --version > /dev/null 2>&1; then
    echo "✅ Application is healthy!"
else
    echo "❌ Application health check failed!"
    exit 1
fi
echo ""

echo "========================================="
echo "✅ Deployment completed successfully!"
echo "========================================="
echo ""
echo "Next steps:"
echo "1. Test the application at your domain"
echo "2. Monitor logs: docker-compose logs -f"
echo "3. Check queue workers: docker-compose logs queue"
echo ""
