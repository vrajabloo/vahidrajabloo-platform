# VahidRajabloo Platform

A Docker-based platform with WordPress (Content) and Laravel (Backend/Dashboard).

## 🚀 Quick Start

### Prerequisites
- Docker Desktop
- Docker Compose
- Git

### Local Development Setup

1. **Clone project:**
```bash
git clone https://github.com/vrajabloo/vahidrajabloo-platform.git
cd vahidrajabloo-platform
```

2. **Copy environment file:**
```bash
cp .env.example .env
# Edit .env and set local passwords
```

3. **Start Local Docker:**
```bash
docker compose -f docker-compose.local.yml up -d --build
```

4. **Access the site:**
- WordPress: http://localhost:8080
- MySQL: localhost:3306

---

## 🌐 Access URLs

| Service | Local URL | Production URL |
|---------|-----------|----------------|
| WordPress | http://localhost:8080 | https://vahidrajabloo.com |
| Laravel Dashboard | - | https://app.vahidrajabloo.com/dashboard |
| Admin Panel | - | https://app.vahidrajabloo.com/admin |

---

## 🐳 Docker Configurations

| File | Environment | Purpose |
|------|-------------|---------|
| `docker-compose.yml` | Production | Full setup with SSL/Certbot |
| `docker-compose.local.yml` | Local Development | Simplified, no SSL, port 8080 |

### Local Development
```bash
# Start
docker compose -f docker-compose.local.yml up -d

# Stop
docker compose -f docker-compose.local.yml down

# View logs
docker logs nginx-local
docker logs wordpress-local

# Rebuild
docker compose -f docker-compose.local.yml up -d --build
```

### Production Deployment
```bash
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"
```

---

## 👥 User Roles

| Role | Name | Purpose |
|------|------|---------|
| `admin` | Admin | Full system access |
| `disabled_user` | Disabled User | Receive services, support |
| `family_user` | Family Disabled | Manage disabled user |
| `supporter_user` | Supporter | Financial support |

---

## 🔧 Admin Panel Features

| Feature | Description |
|---------|-------------|
| Users | Full CRUD, role management |
| Projects | Project management |
| Incomes | Income tracking (USD) |
| Points | Point transactions |
| Wallet | Wallet deposits/withdrawals |
| Settings | System configuration |
| WordPress | SSO link to WordPress admin |

---

## 👤 User Dashboard Features

| Feature | Description |
|---------|-------------|
| MyProjectResource | View user's projects |
| MyWalletResource | Wallet transactions |
| MyPointsResource | Points history |
| MyIncomeResource | Income history |

---

## 📁 Project Structure

```
vahidrajabloo-platform/
├── docker-compose.yml          # Production Docker config
├── docker-compose.local.yml    # Local development Docker config
├── .env.example                # Environment template
├── deploy.sh                   # Deployment script
├── backup.sh                   # Backup script
├── ARCHITECTURE.md             # Platform architecture
├── DEPLOYMENT.md               # Deployment guide
├── docker/
│   ├── nginx/
│   │   ├── default.conf        # Production Nginx config
│   │   └── local.conf          # Local Nginx config
│   ├── laravel/
│   │   └── Dockerfile          # Custom Laravel Docker
│   ├── wordpress/
│   │   └── Dockerfile          # Custom WordPress Docker
│   └── mysql/
│       └── init.sql            # Database init script
├── laravel/                    # Laravel project
│   └── app/
│       ├── Models/             # User, Project, Income, etc.
│       └── Filament/           # Admin panel resources
└── wordpress/                  # WordPress files
    └── wp-content/
        ├── themes/
        │   └── vahidrajabloo-theme/  # Custom theme (Git tracked)
        └── mu-plugins/
            └── laravel-sso.php       # SSO integration (Git tracked)
```

---

## 🔧 Useful Commands

### Local Development
```bash
# Start local environment
docker compose -f docker-compose.local.yml up -d

# Stop local environment
docker compose -f docker-compose.local.yml down

# View WordPress logs
docker logs wordpress-local -f

# Access WordPress container
docker exec -it wordpress-local bash

# Access MySQL
docker exec -it mysql-local mysql -u wordpress_user -p
```

### Production (via SSH)
```bash
# Deploy
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"

# View logs
ssh deploy@116.203.78.31 "docker logs nginx --tail 50"

# Status
ssh deploy@116.203.78.31 "docker ps"

# Rollback
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh"
```

---

## 🔒 Security

- ✅ UFW Firewall (ports 22, 80, 443)
- ✅ SSL/HTTPS via Let's Encrypt
- ✅ Cloudflare WAF protection
- ✅ DISALLOW_FILE_EDIT / DISALLOW_FILE_MODS
- ✅ File integrity monitoring
- ✅ Deploy logging & audit trail
- ✅ Automated backups (daily)
- ✅ Rollback system ready
- ✅ Non-root deploy user

---

## 📁 Security Scripts

| Script | Purpose |
|--------|---------|
| `deploy.sh` | Safe deployment with logging |
| `rollback.sh` | Emergency rollback |
| `scripts/file-monitor.sh` | File integrity monitoring |
| `scripts/deploy-log.sh` | Deploy audit trail |

---

## 🖥️ Server Info

| Item | Value |
|------|-------|
| IP | 116.203.78.31 |
| Provider | Hetzner |
| OS | Ubuntu |
| SSH User | `deploy` (recommended) or `root` (emergency only) |

---

## ⚠️ Important Notes

- **Never** commit `.env` to Git
- **Never** edit files directly on server
- Change passwords in production
- Use `rollback.sh` for emergencies
- Follow `DEPLOYMENT.md` for deployments
- Check `docs/SECURITY_POLICY.md` for full policy
- Use `deploy@` user instead of `root@` for SSH
