# 🚀 Deployment Guide

## 🔐 Golden Rule

```
❌ Never edit code directly on server
✅ Only GitHub → deploy.sh
```

---

## 📋 Quick Reference

| Action | Command |
|--------|---------|
| Deploy | `ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"` |
| View logs | `ssh root@116.203.78.31 "docker logs nginx --tail 50"` |
| Restart | `ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && docker compose restart"` |
| Status | `ssh root@116.203.78.31 "docker ps"` |
| Backup | `ssh root@116.203.78.31 "/var/www/vahidrajabloo-platform/backup.sh"` |

---

## 📦 Deployment Flow

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   LOCAL     │────▶│   GITHUB    │────▶│   SERVER    │
│   Edit      │     │   Push      │     │  deploy.sh  │
└─────────────┘     └─────────────┘     └─────────────┘
```

### Step 1: Make Changes (Local)
```bash
cd ~/Desktop/My\ Web\ Site/vahidrajabloo-platform
# Edit files...
# Test locally
docker-compose up -d
```

### Step 2: Commit & Push
```bash
git add .
git commit -m "feat: description"
git push origin main
```

### Step 3: Deploy
```bash
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"
```

---

## 🔄 First Time Server Setup

```bash
# On server
cd /var/www
git clone https://github.com/YOUR_USERNAME/vahidrajabloo-platform.git
cd vahidrajabloo-platform

# Copy environment
cp .env.production .env

# Make scripts executable
chmod +x deploy.sh backup.sh firewall-setup.sh ssl-setup.sh

# Setup firewall
./firewall-setup.sh

# Initial deployment
./deploy.sh

# Run migrations
docker compose exec laravel php artisan migrate --force

# Create admin user
docker compose exec laravel php artisan tinker --execute="App\Models\User::create(['name'=>'Admin','email'=>'admin@vahidrajabloo.com','password'=>bcrypt('YOUR_PASSWORD'),'role'=>'admin']);"
```

---

## 🔒 Security Setup (Already Done)

| Item | Status | Command |
|------|--------|---------|
| Firewall | ✅ Active | `ufw status` |
| SSL | ✅ Installed | Auto-renews daily 3am |
| Backup | ✅ Active | Daily 2am |
| Log Rotation | ✅ Active | Max 10MB × 3 files |

---

## 🚨 Emergency Commands

| Situation | Command |
|-----------|---------|
| Rollback | `git checkout HEAD~1 -- . && docker compose up -d --build` |
| Force rebuild | `docker compose down && docker compose up -d --build` |
| View errors | `docker logs nginx 2>&1 \| tail -50` |
| Restart all | `docker compose restart` |
| Manual backup | `./backup.sh` |
| SSH to server | `ssh root@116.203.78.31` |

---

## 📊 Server Info

| Item | Value |
|------|-------|
| IP | 116.203.78.31 |
| Provider | Hetzner |
| OS | Ubuntu |
| Domain | vahidrajabloo.com |
| SSL | Let's Encrypt |
| CDN | Cloudflare (Full Strict) |

---

## 🔄 WordPress Database Sync

> ⚠️ WordPress content (posts, pages, settings) is stored in **MySQL database**, NOT in Git!

### Sync Local → Server:

```bash
# 1. Export from local
docker exec mysql mysqldump -u wpuser -pBp4VbST1ELlZEGw3ZMcZPYJclUmfemeb wordpress 2>/dev/null > wordpress_backup.sql

# 2. Copy to server
scp wordpress_backup.sql root@116.203.78.31:/var/www/vahidrajabloo-platform/

# 3. Import on server
ssh root@116.203.78.31 "cat /var/www/vahidrajabloo-platform/wordpress_backup.sql | docker exec -i mysql mysql -u wpuser -pBp4VbST1ELlZEGw3ZMcZPYJclUmfemeb wordpress"

# 4. Update URLs
ssh root@116.203.78.31 "docker exec mysql mysql -u wpuser -pBp4VbST1ELlZEGw3ZMcZPYJclUmfemeb wordpress -e \"UPDATE wp_options SET option_value='https://vahidrajabloo.com' WHERE option_name IN ('siteurl','home');\""
```

### What syncs via Git vs Database:

| Item | Git | Database |
|------|-----|----------|
| Theme files | ✅ | |
| Plugin code | ✅ | |
| Posts/Pages | | ✅ |
| Settings | | ✅ |
| Menus | | ✅ |
| Uploads | ❌ (.gitignore) | |

---

## ⚠️ Never Do

- ❌ Edit files directly on server
- ❌ Run `docker compose down -v` (deletes database!)
- ❌ Change .env on server without updating .env.production locally
- ❌ Expose database port publicly
- ❌ Use weak passwords
