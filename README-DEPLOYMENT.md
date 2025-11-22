# Boardify - Production Deployment Guide

This guide will help you deploy the Boardify application to production using Docker.

## Table of Contents
- [Prerequisites](#prerequisites)
- [Initial Setup](#initial-setup)
- [Docker Deployment](#docker-deployment)
- [Environment Configuration](#environment-configuration)
- [Database Setup](#database-setup)
- [Production Optimization](#production-optimization)
- [Monitoring and Maintenance](#monitoring-and-maintenance)
- [Troubleshooting](#troubleshooting)

## Prerequisites

Before deploying Boardify to production, ensure you have:

- **Server Requirements:**
  - Ubuntu 20.04+ or similar Linux distribution
  - At least 2GB RAM (4GB+ recommended)
  - 20GB free disk space
  - Docker and Docker Compose installed
  - Git installed

- **Domain & SSL:**
  - A registered domain name pointing to your server
  - SSL certificate (Let's Encrypt recommended)

- **Third-party Services:**
  - Pusher account for real-time features (https://pusher.com)
  - SMTP service for emails (SendGrid, Mailgun, AWS SES, etc.)

## Initial Setup

### 1. Install Docker and Docker Compose

```bash
# Update package index
sudo apt update

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Add your user to docker group
sudo usermod -aG docker $USER

# Verify installation
docker --version
docker-compose --version
```

### 2. Clone the Repository

```bash
cd /var/www
sudo git clone https://github.com/AwaisDotGeek/_Boardify_.git
cd _Boardify_
```

## Environment Configuration

### 1. Create Production Environment File

```bash
cp .env.example .env
```

### 2. Configure Environment Variables

Edit the `.env` file with your production values:

```bash
nano .env
```

**Critical variables to configure:**

```env
APP_NAME=Boardify
APP_ENV=production
APP_KEY=                    # Generate with: php artisan key:generate
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=boardify_production
DB_USERNAME=boardify_user
DB_PASSWORD=YOUR_SECURE_PASSWORD_HERE

# Redis Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=redis

# Email (Example with Mailgun)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your_mailgun_username
MAIL_PASSWORD=your_mailgun_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"

# Pusher (Required for real-time features)
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=us2
```

### 3. Generate Application Key

```bash
docker-compose run --rm app php artisan key:generate
```

## Docker Deployment

### 1. Build and Start Containers

```bash
# Build images
docker-compose build

# Start containers in detached mode
docker-compose up -d
```

### 2. Verify Containers are Running

```bash
docker-compose ps
```

You should see the following services running:
- `boardify-app` (Laravel application)
- `boardify-nginx` (Web server)
- `boardify-mysql` (Database)
- `boardify-redis` (Cache & Queue)
- `boardify-queue` (Queue worker)
- `boardify-scheduler` (Task scheduler)

## Database Setup

### 1. Run Migrations

```bash
docker-compose exec app php artisan migrate --force
```

### 2. (Optional) Seed Database

If you have seeders configured:

```bash
docker-compose exec app php artisan db:seed --force
```

## Production Optimization

### 1. Install Dependencies

```bash
# Install PHP dependencies (production only)
docker-compose exec app composer install --optimize-autoloader --no-dev

# Install and build frontend assets
docker-compose exec app npm ci
docker-compose exec app npm run build
```

### 2. Cache Configuration

```bash
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
docker-compose exec app php artisan event:cache
```

Or use the optimization script:

```bash
docker-compose exec app composer optimize
```

### 3. Set Proper Permissions

```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/bootstrap/cache
```

## SSL/TLS Configuration

### 1. Install Certbot (Let's Encrypt)

```bash
sudo apt install certbot
```

### 2. Generate SSL Certificate

```bash
sudo certbot certonly --standalone -d your-domain.com -d www.your-domain.com
```

### 3. Copy Certificates to Docker Volume

```bash
sudo cp /etc/letsencrypt/live/your-domain.com/fullchain.pem docker/nginx/ssl/cert.pem
sudo cp /etc/letsencrypt/live/your-domain.com/privkey.pem docker/nginx/ssl/key.pem
```

### 4. Enable HTTPS in Nginx

Edit `docker/nginx/conf.d/default.conf` and uncomment the HTTPS server block. Update the `server_name` to match your domain.

### 5. Restart Nginx

```bash
docker-compose restart nginx
```

### 6. Setup Auto-Renewal

```bash
sudo crontab -e

# Add this line
0 0 * * * certbot renew --quiet && cp /etc/letsencrypt/live/your-domain.com/*.pem /var/www/_Boardify_/docker/nginx/ssl/ && docker-compose -f /var/www/_Boardify_/docker-compose.yml restart nginx
```

## Monitoring and Maintenance

### View Application Logs

```bash
# All logs
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f mysql
```

### Laravel Logs

```bash
docker-compose exec app tail -f storage/logs/laravel.log
```

### Restart Services

```bash
# Restart all services
docker-compose restart

# Restart specific service
docker-compose restart app
```

### Update Application

```bash
# Pull latest changes
git pull origin main

# Rebuild and restart
docker-compose down
docker-compose build
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate --force

# Clear and recache
docker-compose exec app composer clear-cache
docker-compose exec app composer optimize
```

### Backup Database

```bash
# Create backup
docker-compose exec mysql mysqldump -u boardify_user -p boardify_production > backup_$(date +%Y%m%d).sql

# Restore backup
docker-compose exec -T mysql mysql -u boardify_user -p boardify_production < backup_20240101.sql
```

## Queue Management

### Monitor Queue

```bash
docker-compose exec app php artisan queue:monitor
```

### Clear Failed Jobs

```bash
docker-compose exec app php artisan queue:flush
```

### Restart Queue Workers

```bash
docker-compose restart queue
```

## Performance Tuning

### PHP-FPM Optimization

Edit the PHP-FPM configuration if needed to handle more concurrent requests.

### Redis Optimization

Monitor Redis memory usage:

```bash
docker-compose exec redis redis-cli INFO memory
```

### Database Optimization

```bash
# Analyze tables
docker-compose exec mysql mysqlcheck -u boardify_user -p --optimize --all-databases
```

## Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] Strong database password set
- [ ] HTTPS enabled with valid SSL certificate
- [ ] Firewall configured (ports 80, 443 only)
- [ ] Regular security updates applied
- [ ] Pusher credentials secured
- [ ] SMTP credentials secured
- [ ] File permissions properly set (775 for storage, www-data owner)
- [ ] `.env` file not publicly accessible
- [ ] `composer install --no-dev` used in production

## Troubleshooting

### Application Not Responding

```bash
# Check if containers are running
docker-compose ps

# Check application logs
docker-compose logs app

# Restart application
docker-compose restart app
```

### Database Connection Issues

```bash
# Check MySQL container
docker-compose logs mysql

# Verify database credentials in .env
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo();
```

### Permission Issues

```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Cache Issues

```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

### Real-time Features Not Working

1. Verify Pusher credentials in `.env`
2. Check browser console for WebSocket errors
3. Ensure `BROADCAST_DRIVER=pusher` is set
4. Restart the application

## Useful Commands

```bash
# Quick deployment script
composer deploy

# Rollback last migration
composer deploy:rollback

# Clear all caches
composer clear-cache

# Optimize for production
composer optimize

# Run tests
composer test

# Fresh database (WARNING: Deletes all data)
composer fresh
```

## Support

For issues and questions:
- GitHub Issues: https://github.com/AwaisDotGeek/_Boardify_/issues
- Laravel Documentation: https://laravel.com/docs
- Docker Documentation: https://docs.docker.com

## License

This project is licensed under the MIT License.
