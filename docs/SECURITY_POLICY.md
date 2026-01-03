# 🔒 Security Policy

**Last Updated:** 2025-12-18

## 🎯 Core Principles

```
1. Git is the SINGLE SOURCE OF TRUTH for code
2. Any change outside Git = 🚨 SECURITY ALERT
3. Server is DISPOSABLE - rebuild anytime
4. Database & uploads = SACRED (never in Git)
5. All deploys = Logged and auditable
```

---

## 🔐 WordPress Hardening (Active)

### wp-config.php Security Defines
```php
define('DISALLOW_FILE_EDIT', true);      // ✅ No dashboard editor
define('DISALLOW_FILE_MODS', true);      // ✅ No plugin/theme installs
define('WP_AUTO_UPDATE_CORE', false);    // ✅ Git-only updates
define('WP_DEBUG', false);               // ✅ Debug disabled
define('FORCE_SSL_ADMIN', true);         // ✅ SSL enforced
```

---

## 📁 File Tracking Rules

| Path | Git Tracked | Source |
|------|-------------|--------|
| `wordpress/wp-content/mu-plugins/` | ✅ YES | SSO code |
| `wordpress/wp-content/themes/vahidrajabloo-theme/` | ✅ YES | Custom theme |
| `wordpress/wp-content/plugins/` | ❌ NO | Managed via dashboard |
| `wordpress/wp-admin/` | ❌ NO | Docker image |
| `wordpress/wp-includes/` | ❌ NO | Docker image |
| `wordpress/wp-config.php` | ❌ NO | Server-specific |
| `wordpress/wp-content/uploads/` | ❌ NO | User data (SACRED) |
| `scripts/` | ✅ YES | Security scripts |

---

## 🛡️ Security Scripts

| Script | Purpose | Usage |
|--------|---------|-------|
| `scripts/file-monitor.sh` | Detect unauthorized file changes | `./file-monitor.sh check` |
| `scripts/deploy-log.sh` | Audit trail for deployments | Auto-runs on deploy |
| `deploy.sh` | Safe deployment | `./deploy.sh` |
| `rollback.sh` | Emergency rollback | `./rollback.sh <commit>` |

### File Integrity Monitoring
```bash
# Create baseline after clean deploy
./scripts/file-monitor.sh baseline

# Check for changes (run via cron)
./scripts/file-monitor.sh check
```

---

## 🚨 Security Alerts

| Condition | Severity | Action |
|-----------|----------|--------|
| Unknown PHP in `/wp-content/` | 🔴 CRITICAL | Delete + investigate |
| PHP in `/wp-content/uploads/` | 🔴 CRITICAL | Malware - delete all |
| `index.php` modified | 🔴 CRITICAL | Restore from Git |
| Unknown admin users | 🔴 CRITICAL | Delete + reset passwords |
| Files edited on server | 🟡 WARNING | Restore from Git |
| Core file mismatch | 🟡 WARNING | Rebuild container |

---

## 🔐 SSO Security

### Critical Files
```
laravel/app/Models/WpLoginToken.php
laravel/app/Http/Controllers/WpAutoLoginController.php
wordpress/wp-content/mu-plugins/laravel-sso.php
```

### Token Requirements
- Length: 64 characters
- Expiry: 5 minutes
- Usage: Single-use only
- Transport: HTTPS only

---

## 🌐 Cloudflare WAF Rules

| Rule | Action |
|------|--------|
| Allow SSO `?sso=1&token=` | Skip |
| Block `/xmlrpc.php` | Block |
| Block PHP in uploads | Block |
| Challenge `/wp-login.php` | Challenge |
| Protect `/wp-admin/` | Challenge |
| Rate limit login | 5/min then block |

---

## 🔄 Incident Recovery

### Step 1: Isolate
```bash
ssh deploy@116.203.78.31 "docker compose stop wordpress"
```

### Step 2: Rollback
```bash
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh HEAD~1"
```

### Step 3: Clean Malware
```bash
ssh deploy@116.203.78.31 "docker exec wordpress find /var/www/html/wp-content/uploads -name '*.php' -delete"
```

### Step 4: Reset Passwords
```bash
# Via Laravel SSO or wp-cli
```

---

## 📋 Weekly Checklist

- [ ] Run `file-monitor.sh check`
- [ ] Review deploy-history.log
- [ ] Check Cloudflare WAF logs
- [ ] Verify no unknown admin users
- [ ] Backup database
- [ ] Check WordPress error logs

---

## 🚫 Never Do

```
❌ Edit files directly on server
❌ Upload files via FTP
❌ Install plugins via wp-admin
❌ Share SSO tokens
❌ Expose database port
❌ Run docker compose down -v
```

---

## ✅ Always Do

```
✅ Deploy via Git only
✅ Use rollback.sh for emergencies
✅ Monitor file integrity
✅ Log all deployments
✅ Use strong passwords
✅ Enable Cloudflare WAF
```
