# VahidRajabloo Platform

A Docker-based platform with WordPress (Content) and Laravel (Backend/Dashboard).

## 🚀 Quick Start

### Prerequisites
- Docker Desktop
- Docker Compose

### Setup

1. **Clone project:**
```bash
git clone <repository-url>
cd vahidrajabloo-platform
```

2. **Copy environment file:**
```bash
cp .env.example .env
# Edit .env and change passwords
```

3. **Start Docker:**
```bash
docker-compose up -d --build
```

4. **Setup hosts file:**
```bash
sudo nano /etc/hosts
# Add:
127.0.0.1 vahidrajabloo.local
::1 vahidrajabloo.local
127.0.0.1 app.vahidrajabloo.local
::1 app.vahidrajabloo.local
```

5. **Run Laravel migrations:**
```bash
docker-compose exec laravel php artisan migrate
```

---

## 🌐 Access URLs

| Service | Local URL | Production URL |
|---------|-----------|----------------|
| WordPress | http://vahidrajabloo.local | https://vahidrajabloo.com |
| Laravel Dashboard | http://app.vahidrajabloo.local/dashboard | https://app.vahidrajabloo.com/dashboard |
| Admin Panel | http://app.vahidrajabloo.local/admin | https://app.vahidrajabloo.com/admin |

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
├── docker-compose.yml      # Docker configuration
├── .env.production         # Production environment (secret)
├── deploy.sh               # Deployment script
├── backup.sh               # Backup script
├── ARCHITECTURE.md         # Platform architecture
├── DEPLOYMENT.md           # Deployment guide
├── docker/
│   ├── nginx/
│   │   └── default.conf    # Nginx configuration
│   ├── laravel/
│   │   └── Dockerfile      # Custom Laravel Docker
│   └── mysql/
│       └── init.sql        # Database init script
├── laravel/                # Laravel project
│   └── app/
│       ├── Models/         # User, Project, Income, etc.
│       └── Filament/       # Admin panel resources
└── wordpress/              # WordPress files
```

---

## 🔧 Useful Commands

```bash
# Start
docker-compose up -d

# Stop
docker-compose down

# View logs
docker-compose logs -f

# Laravel shell
docker-compose exec laravel bash

# Run migrations
docker-compose exec laravel php artisan migrate

# Clear cache
docker-compose exec laravel php artisan cache:clear
```

---

## 🔒 Security

- ✅ UFW Firewall (ports 22, 80, 443)
- ✅ SSL/HTTPS via Let's Encrypt
- ✅ Strong passwords (32 char)
- ✅ Rate limiting in Nginx
- ✅ Automated backups (daily 2am)
- ✅ SSL auto-renewal (daily 3am)

---

## ⚠️ Important Notes

- **Never** commit `.env` to Git
- Change passwords in production
- Use SSL/TLS in production
- Follow `DEPLOYMENT.md` for deployments
