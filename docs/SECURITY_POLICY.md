# 🔒 Security Policy

## 🎯 Core Principles

```
1. Git is the SINGLE SOURCE OF TRUTH for code
2. Any change outside Git = 🚨 SECURITY ALERT
3. Core WordPress files = Docker image only
4. mu-plugins = Git tracked (SSO critical)
5. Database & uploads = SACRED (never in Git)
```

---

## 📁 File Tracking Rules

| Path | Git Tracked | Source |
|------|-------------|--------|
| `wordpress/wp-content/mu-plugins/` | ✅ YES | Your code (SSO) |
| `wordpress/wp-content/themes/hello-elementor/` | ✅ YES | Your customizations |
| `wordpress/wp-content/plugins/` | ⚠️ Selective | Custom plugins only |
| `wordpress/wp-admin/` | ❌ NO | Docker WordPress image |
| `wordpress/wp-includes/` | ❌ NO | Docker WordPress image |
| `wordpress/index.php` | ❌ NO | Docker WordPress image |
| `wordpress/wp-config.php` | ❌ NO | Server-specific |
| `wordpress/wp-content/uploads/` | ❌ NO | User data (SACRED) |

---

## 🚨 Security Alerts

### When to Investigate:

| Condition | Action |
|-----------|--------|
| `index.php` modified | 🔴 CRITICAL - Check for malware |
| Unknown files in `wp-content/` | 🔴 Investigate immediately |
| mu-plugins mismatch with Git | 🔴 Restore from Git |
| Core files different from Docker | 🟡 Rebuild container |
| Files edited directly on server | 🟡 Violates Golden Rule |

---

## 🔐 SSO Security

The Laravel-WordPress SSO uses:
- **Token-based authentication** (64-char random)
- **5-minute expiry**
- **One-time use tokens**
- **API validation between services**

### SSO Critical Files:
```
laravel/app/Models/WpLoginToken.php
laravel/app/Http/Controllers/WpAutoLoginController.php
wordpress/wp-content/mu-plugins/laravel-sso.php
```

> ⚠️ Any modification to these files outside Git = SECURITY BREACH

---

## 🛡️ Integrity Verification

### Check Core Files:
```bash
# On server - verify WordPress core against Docker image
ssh root@116.203.78.31 "docker exec wordpress wp core verify-checksums --allow-root"
```

### Check mu-plugins Match Git:
```bash
# On server - diff mu-plugins
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && git diff wordpress/wp-content/mu-plugins/"
```

### Check for Unknown Files:
```bash
# On server - find files not in Git
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && git status wordpress/wp-content/"
```

---

## 🔄 Recovery from Security Incident

### Step 1: Isolate
```bash
# Stop the site
ssh root@116.203.78.31 "docker compose stop wordpress"
```

### Step 2: Restore Code from Git
```bash
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && git checkout -- ."
```

### Step 3: Rebuild Containers (Clean Core)
```bash
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && docker compose up -d --build --force-recreate"
```

### Step 4: Verify
```bash
ssh root@116.203.78.31 "docker exec wordpress wp core verify-checksums --allow-root"
```

---

## 📋 Weekly Security Checklist

- [ ] Run `wp core verify-checksums`
- [ ] Check `git status` on server (should be clean)
- [ ] Review WordPress login logs
- [ ] Check Laravel SSO token table for anomalies
- [ ] Verify no unknown files in wp-content
- [ ] Backup database

---

## 🚫 Never Do

```
❌ Edit files directly on server
❌ Upload files via FTP
❌ Install plugins via wp-admin on production
❌ Give wp-admin access to non-admins
❌ Share SSO tokens
❌ Disable mu-plugins
```

---

## ✅ Always Do

```
✅ Deploy code via Git only
✅ Use Docker for WordPress core
✅ Keep mu-plugins in Git
✅ Monitor for file changes
✅ Use strong passwords
✅ Keep backups
```
