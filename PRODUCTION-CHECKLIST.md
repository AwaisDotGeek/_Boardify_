# Production Deployment Checklist

Use this checklist before deploying Boardify to production.

## Pre-Deployment Checklist

### Environment Configuration
- [ ] `.env` file created and configured with production values
- [ ] `APP_ENV=production` set
- [ ] `APP_DEBUG=false` set
- [ ] `APP_KEY` generated (run `php artisan key:generate`)
- [ ] `APP_URL` set to production domain
- [ ] Database credentials configured and tested
- [ ] Redis connection configured and tested
- [ ] Pusher credentials configured for real-time features
- [ ] SMTP/Email service configured and tested
- [ ] All sensitive credentials secured (not in version control)

### Security
- [ ] HTTPS/SSL certificate installed and configured
- [ ] Force HTTPS enabled in `.htaccess` and nginx config
- [ ] Security headers configured (X-Frame-Options, CSP, HSTS, etc.)
- [ ] `.env` file has proper permissions (600) and not publicly accessible
- [ ] File permissions set correctly (storage: 775, www-data owner)
- [ ] Directory browsing disabled
- [ ] Server signature hidden
- [ ] CSRF protection enabled
- [ ] XSS protection enabled
- [ ] SQL injection protection verified
- [ ] Rate limiting configured for API endpoints
- [ ] Failed login attempt limiting enabled

### Database
- [ ] Database migrations created and reviewed
- [ ] Migration files tested in staging environment
- [ ] Database backups configured and tested
- [ ] Database connection pooling configured
- [ ] Foreign key constraints verified
- [ ] Database indexes optimized
- [ ] Database user has minimum required privileges

### Performance
- [ ] Redis configured for cache
- [ ] Redis configured for sessions
- [ ] Redis configured for queue
- [ ] Config cached (`php artisan config:cache`)
- [ ] Routes cached (`php artisan route:cache`)
- [ ] Views cached (`php artisan view:cache`)
- [ ] Events cached (`php artisan event:cache`)
- [ ] Composer autoloader optimized (`--optimize-autoloader --no-dev`)
- [ ] Frontend assets built for production (`npm run build`)
- [ ] Gzip compression enabled
- [ ] Browser caching headers configured
- [ ] CDN configured (if applicable)
- [ ] Image optimization completed
- [ ] Database query optimization completed

### Docker & Infrastructure
- [ ] Docker and Docker Compose installed on server
- [ ] Docker images built successfully
- [ ] All containers starting correctly
- [ ] Container health checks passing
- [ ] Volume mounts configured correctly
- [ ] Network connectivity between containers verified
- [ ] Port mappings configured correctly (80, 443)
- [ ] Container restart policies set (`unless-stopped`)

### Application
- [ ] All dependencies installed via Composer (production only)
- [ ] All NPM packages installed
- [ ] Laravel queue worker running
- [ ] Laravel scheduler configured (cron job)
- [ ] Real-time broadcasting tested (Pusher)
- [ ] Email sending tested
- [ ] File uploads tested
- [ ] Error logging configured and tested
- [ ] Application logs rotated

### Monitoring & Logging
- [ ] Application logging configured (`LOG_CHANNEL=daily`)
- [ ] Log rotation configured
- [ ] Error tracking service integrated (optional: Sentry, Bugsnag)
- [ ] Application monitoring configured (optional: New Relic, DataDog)
- [ ] Server monitoring configured (CPU, RAM, Disk)
- [ ] Database monitoring configured
- [ ] Uptime monitoring configured
- [ ] Alert notifications configured

### Backup & Recovery
- [ ] Database backup strategy implemented
- [ ] Automated daily backups configured
- [ ] Backup restoration tested
- [ ] File storage backup configured
- [ ] Disaster recovery plan documented
- [ ] Backup retention policy defined

### CI/CD
- [ ] GitHub Actions workflows configured
- [ ] CI tests passing
- [ ] Code quality checks passing
- [ ] Security audits passing
- [ ] Deployment secrets configured in GitHub
- [ ] SSH keys configured for deployment
- [ ] Deployment rollback plan tested

### Domain & DNS
- [ ] Domain name configured
- [ ] DNS A record pointing to server IP
- [ ] SSL certificate valid and not expiring soon
- [ ] www redirect configured (if needed)
- [ ] DNS propagation completed

### Third-party Services
- [ ] Pusher account created and configured
- [ ] Email service account configured (SendGrid, Mailgun, etc.)
- [ ] API keys for external services configured
- [ ] Service rate limits understood
- [ ] Service monitoring/alerts configured

### Testing
- [ ] All unit tests passing
- [ ] All feature tests passing
- [ ] Manual testing completed on staging
- [ ] User acceptance testing completed
- [ ] Load testing completed (if applicable)
- [ ] Browser compatibility tested
- [ ] Mobile responsiveness tested
- [ ] Security penetration testing completed (recommended)

### Documentation
- [ ] Deployment documentation reviewed (README-DEPLOYMENT.md)
- [ ] API documentation updated (if applicable)
- [ ] Environment variables documented
- [ ] Common troubleshooting scenarios documented
- [ ] Rollback procedures documented
- [ ] Team members trained on deployment process

### Post-Deployment
- [ ] Application accessible via production URL
- [ ] All features working correctly
- [ ] Real-time features (chat, game) tested
- [ ] Email notifications working
- [ ] Database queries performing well
- [ ] No errors in application logs
- [ ] No errors in web server logs
- [ ] Performance metrics acceptable
- [ ] Security scan completed
- [ ] Uptime monitoring active

## Emergency Contacts
- Server Provider: _______________
- Domain Registrar: _______________
- SSL Certificate Provider: _______________
- Email Service: _______________
- Pusher Support: _______________
- Database Administrator: _______________
- DevOps Lead: _______________

## Rollback Plan
If deployment fails:
1. Run: `composer deploy:rollback`
2. Restore database from last backup
3. Restart Docker containers: `docker-compose restart`
4. Check logs: `docker-compose logs -f`
5. Notify team

## Maintenance Window
- Scheduled maintenance time: _______________
- Expected downtime: _______________
- User notification sent: [ ]
- Status page updated: [ ]

---

**Deployment Date:** _______________
**Deployed By:** _______________
**Version/Tag:** _______________
**Sign-off:** _______________
