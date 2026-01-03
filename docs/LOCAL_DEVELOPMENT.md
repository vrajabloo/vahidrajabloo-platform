# 🖥️ Local Development Guide

**Last Updated:** 2025-01-03

## 🚀 Quick Start

### Prerequisites
- Docker Desktop (installed and running)
- Git
- Terminal access

### Setup Steps

1. **Clone the repository:**
```bash
git clone https://github.com/vrajabloo/vahidrajabloo-platform.git
cd vahidrajabloo-platform
```

2. **Create environment file:**
```bash
cp .env.example .env
```

3. **Start Docker containers:**
```bash
docker compose -f docker-compose.local.yml up -d --build
```

4. **Wait for MySQL to initialize** (first time only, ~30 seconds)

5. **Access the site:**
   - WordPress: http://localhost:8080
   - Complete WordPress installation wizard

---

## 🐳 Container Architecture

| Container | Image | Port | Purpose |
|-----------|-------|------|---------|
| `nginx-local` | nginx:alpine | 8080→80 | Reverse Proxy |
| `mysql-local` | mysql:8.0 | 3306 | Database |
| `wordpress-local` | Custom Build | 9000 (internal) | PHP-FPM WordPress |
| `laravel-local` | Custom Build | 9000 (internal) | PHP-FPM Laravel |

### Volume Strategy

| Volume/Mount | Content | Persistence |
|--------------|---------|-------------|
| `wordpress-data` | WordPress Core | Named Volume ✅ |
| `mysql-data-local` | Database | Named Volume ✅ |
| `./wordpress/wp-content/themes` | Custom themes | Git ✅ |
| `./wordpress/wp-content/plugins` | Custom plugins | Git ✅ |
| `./laravel` | Laravel code | Git ✅ |

---

## 🔧 Common Commands

### Start/Stop
```bash
# Start containers
docker compose -f docker-compose.local.yml up -d

# Stop containers (keep data)
docker compose -f docker-compose.local.yml down

# Stop and remove volumes (DELETES DATA!)
docker compose -f docker-compose.local.yml down -v
```

### Logs
```bash
# All logs
docker compose -f docker-compose.local.yml logs -f

# Specific container
docker logs nginx-local -f
docker logs wordpress-local -f
docker logs mysql-local -f
docker logs laravel-local -f
```

### Rebuild
```bash
# Rebuild after Dockerfile changes
docker compose -f docker-compose.local.yml up -d --build

# Force full rebuild (no cache)
docker compose -f docker-compose.local.yml build --no-cache
docker compose -f docker-compose.local.yml up -d
```

### Access Containers
```bash
# WordPress shell
docker exec -it wordpress-local bash

# MySQL CLI
docker exec -it mysql-local mysql -u wordpress_user -plocal_password_123

# Laravel shell
docker exec -it laravel-local bash
```

---

## 🔄 Development Workflow

### Theme Development
1. Edit files in `wordpress/wp-content/themes/vahidrajabloo-theme/`
2. Changes are immediately visible at http://localhost:8080
3. Commit and push to Git
4. Deploy to production

### Plugin Development
1. Edit files in `wordpress/wp-content/plugins/your-plugin/`
2. Test locally
3. Commit and push to Git
4. Deploy to production

### Laravel Development
1. Edit files in `laravel/`
2. Run migrations if needed:
   ```bash
   docker exec laravel-local php artisan migrate
   ```
3. Commit and push to Git
4. Deploy to production

---

## 🗄️ Database

### Connection Details
| Setting | Value |
|---------|-------|
| Host | `mysql-local` (inside Docker) or `localhost` (outside) |
| Port | 3306 |
| Database | wordpress |
| Username | wordpress_user |
| Password | local_password_123 |
| Root Password | root_password_123 |

### Export Database
```bash
docker exec mysql-local mysqldump -u root -proot_password_123 wordpress > backup.sql
```

### Import Database
```bash
docker exec -i mysql-local mysql -u root -proot_password_123 wordpress < backup.sql
```

---

## 🆚 Local vs Production

| Aspect | Local | Production |
|--------|-------|------------|
| Docker file | `docker-compose.local.yml` | `docker-compose.yml` |
| URL | http://localhost:8080 | https://vahidrajabloo.com |
| SSL | ❌ No | ✅ Let's Encrypt |
| Certbot | ❌ No | ✅ Yes |
| WP_DEBUG | true | false |
| DISALLOW_FILE_EDIT | false | true |
| Database | Local container | Production MySQL |

---

## ⚠️ Troubleshooting

### Port 8080 already in use
```bash
# Find what's using port 8080
lsof -i :8080

# Kill the process or change port in docker-compose.local.yml
```

### WordPress shows installation page again
```bash
# Check if wp-config.php exists
ls wordpress/wp-config.php

# Check MySQL is running
docker ps | grep mysql-local

# Check MySQL logs
docker logs mysql-local
```

### Changes not showing
```bash
# Clear browser cache
# Or restart containers
docker compose -f docker-compose.local.yml restart
```

### Permission issues
```bash
# Fix WordPress permissions inside container
docker exec wordpress-local chown -R www-data:www-data /var/www/html
```

---

## 🔒 Files NOT in Git

These files are local-only and ignored by Git:

| File | Reason |
|------|--------|
| `.env` | Contains passwords |
| `wordpress/wp-config.php` | Environment-specific |
| `docker-compose.local.yml` | Local config |
| `docker/nginx/local.conf` | Local Nginx config |
| `docker/mysql/init.sql` | Local DB init |

---

## 🚀 Deploy to Production

After testing locally:

```bash
# Commit changes
git add .
git commit -m "Your change description"
git push origin main

# Deploy to server
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"
```
