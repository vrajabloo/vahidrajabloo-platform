# 🚀 WordPress Production Deployment Guide

> **SACRED DATA RULE**: Database and uploads are PRODUCTION DATA. Never overwrite!

---

## 🔐 Golden Rules

```
1. CODE goes through Git      → themes, plugins, mu-plugins, PHP/CSS/JS
2. DATABASE stays in MySQL    → posts, pages, settings, menus, widgets
3. UPLOADS stay on server     → media files in wp-content/uploads
4. wp-config.php is SACRED   → never replace in production
5. Local ≠ Production DB      → never sync databases
```

---

## 📁 Repository Structure

```
vahidrajabloo-platform/
├── wordpress/                      # WordPress Core
│   ├── wp-admin/                   # ❌ .gitignore (auto-updated)
│   ├── wp-includes/                # ❌ .gitignore (auto-updated)
│   ├── wp-config.php               # ❌ .gitignore (environment-specific)
│   └── wp-content/
│       ├── themes/
│       │   ├── hello-elementor/    # ✅ Git tracked
│       │   └── my-custom-theme/    # ✅ Git tracked
│       ├── plugins/
│       │   ├── elementor/          # ❌ .gitignore (installed via WP)
│       │   └── my-custom-plugin/   # ✅ Git tracked (custom only)
│       ├── mu-plugins/             # ✅ Git tracked (all)
│       ├── uploads/                # ❌ .gitignore (SACRED)
│       ├── cache/                  # ❌ .gitignore
│       ├── upgrade/                # ❌ .gitignore
│       └── languages/              # ❌ .gitignore (downloaded)
├── docker/
├── laravel/
└── deploy.sh
```

---

## 📋 What Goes Where

| Item | Location | Git? | Deploy? | Notes |
|------|----------|------|---------|-------|
| WordPress Core | `/` | ❌ | Auto | Updated via Docker image |
| wp-config.php | `/` | ❌ | Never | Environment-specific |
| Custom Theme | `wp-content/themes/` | ✅ | ✅ | Your code |
| Child Theme | `wp-content/themes/` | ✅ | ✅ | Your code |
| Custom Plugins | `wp-content/plugins/` | ✅ | ✅ | Your code |
| Marketplace Plugins | `wp-content/plugins/` | ❌ | Via WP Admin | Installed on each env |
| mu-plugins | `wp-content/mu-plugins/` | ✅ | ✅ | Always loaded |
| Uploads | `wp-content/uploads/` | ❌ | Never | SACRED |
| Database | MySQL | ❌ | Never | SACRED |

---

## 🔄 Deployment Flow

```
┌─────────────────────────────────────────────────────────────┐
│                    DEPLOYMENT FLOW                           │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  LOCAL                  GITHUB                 PRODUCTION    │
│  ┌────┐                ┌────┐                  ┌────┐       │
│  │Edit│ ───git push──▶ │Repo│ ───git pull────▶ │Code│       │
│  │Code│                │    │                  │Only│       │
│  └────┘                └────┘                  └────┘       │
│                                                              │
│  ┌────┐                                        ┌────┐       │
│  │ DB │  ─────────────  ✖️  ────────────────▶  │ DB │       │
│  │    │              NEVER SYNC                │    │       │
│  └────┘                                        └────┘       │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### Deploy Commands:

```bash
# Standard deploy (CODE ONLY)
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"

# Quick sync without rebuild
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && git pull origin main"
```

---

## 🛡️ What deploy.sh Does (and Does NOT)

### ✅ DOES:
- Pull latest code from GitHub
- Rebuild Docker containers
- Restart services
- Clear cache

### ❌ DOES NOT:
- Touch MySQL database
- Modify wp-content/uploads
- Replace wp-config.php
- Import any SQL files

---

## 💾 Backup Strategy

### Automatic (Cron):
```bash
# Added to crontab on server:
0 2 * * * /var/www/vahidrajabloo-platform/backup.sh  # Database daily 2am
```

### Manual Backup:
```bash
# Database
ssh deploy@116.203.78.31 "/var/www/vahidrajabloo-platform/backup.sh"

# Uploads (run periodically)
ssh deploy@116.203.78.31 "tar -czf /var/backups/vahidrajabloo/uploads_$(date +%Y%m%d).tar.gz -C /var/www/vahidrajabloo-platform/wordpress/wp-content uploads"
```

### Backup Contents:
| What | How Often | Location |
|------|-----------|----------|
| WordPress DB | Daily 2am | `/var/backups/vahidrajabloo/wordpress_*.sql.gz` |
| Laravel DB | Daily 2am | `/var/backups/vahidrajabloo/laravel_*.sql.gz` |
| Uploads | Weekly (manual) | `/var/backups/vahidrajabloo/uploads_*.tar.gz` |

---

## 🎨 Elementor & Settings Strategy

### The Problem:
Elementor stores page designs in the DATABASE, not files!

### Solution:
**DO NOT sync databases.** Instead:

1. **For templates**: Use Elementor's Export/Import feature
   - Elementor → Templates → Export Template
   - On production: Import Template

2. **For global settings**: Configure manually on each environment

3. **For widgets/styles**: Use Elementor Theme Style (stored in DB per environment)

### Safe Migration Process:
```
1. Export template from local
2. SCP .json file to production
3. Import in production via Elementor UI
4. NEVER overwrite the database
```

---

## 🌍 Environment Separation

### Local Development:
```
Domain:    vahidrajabloo.local
Database:  Local MySQL container
wp-config: Local settings
Theme:     Editable, tracked in Git
```

### Production:
```
Domain:    vahidrajabloo.com
Database:  Production MySQL (SACRED)
wp-config: Production settings (SACRED)
Theme:     Deployed from Git
```

### Key Differences:

| Setting | Local | Production |
|---------|-------|------------|
| WP_DEBUG | true | false |
| DISALLOW_FILE_EDIT | false | true |
| WP_HOME | http://vahidrajabloo.local | https://vahidrajabloo.com |
| WP_SITEURL | http://vahidrajabloo.local | https://vahidrajabloo.com |

---

## ⚠️ Never Do

```
❌ Import database from local to production
❌ Overwrite wp-content/uploads
❌ Replace wp-config.php on server
❌ Run docker compose down -v (deletes database!)
❌ Edit files directly on server
❌ Use FTP to upload theme changes
❌ Install plugins via FTP (breaks auto-update)
```

---

## 🚨 Emergency Recovery

### If database is corrupted:
```bash
# Restore from backup
gunzip -c /var/backups/vahidrajabloo/wordpress_YYYYMMDD.sql.gz | \
  docker exec -i mysql mysql -u wpuser -pYOUR_PASSWORD wordpress
```

### If uploads are lost:
```bash
# Restore from backup
tar -xzf /var/backups/vahidrajabloo/uploads_YYYYMMDD.tar.gz \
  -C /var/www/vahidrajabloo-platform/wordpress/wp-content/
```

### If code is broken:
```bash
# Rollback to previous commit
cd /var/www/vahidrajabloo-platform
git log --oneline -5  # Find good commit
git checkout abc123 -- wordpress/
docker compose restart
```

---

## 📊 Quick Reference

| Action | Command |
|--------|---------|
| Deploy code | `ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"` |
| Backup DB | `ssh deploy@116.203.78.31 "/var/www/vahidrajabloo-platform/backup.sh"` |
| Backup uploads | `ssh deploy@116.203.78.31 "tar -czf /var/backups/vahidrajabloo/uploads_$(date +%Y%m%d).tar.gz -C /var/www/vahidrajabloo-platform/wordpress/wp-content uploads"` |
| View logs | `ssh deploy@116.203.78.31 "docker logs wordpress --tail 50"` |
| Clear WP cache | `ssh deploy@116.203.78.31 "docker exec wordpress wp cache flush --allow-root"` |
| Restart | `ssh deploy@116.203.78.31 "docker compose restart wordpress"` |
