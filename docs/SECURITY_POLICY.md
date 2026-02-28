# 🔒 Security Policy

**Last Updated:** 2026-02-28

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
| `wordpress/wp-content/mu-plugins/` | ✅ YES | SSO + fingerprint hardening |
| `wordpress/wp-content/themes/vahidrajabloo-theme/` | ✅ YES | Custom theme |
| `wordpress/wp-content/plugins/` | ❌ NO | Managed via wp-cli (server-side) |
| `wordpress/wp-admin/` | ❌ NO | Docker image |
| `wordpress/wp-includes/` | ❌ NO | Docker image |
| `wordpress/wp-config.php` | ❌ NO | Server-specific |
| `wordpress/wp-content/uploads/` | ❌ NO | User data (SACRED) |
| `scripts/` | ✅ YES | Security scripts |

---

## 🚪 WordPress Entry Control (Active)

- Direct access to `https://vahidrajabloo.com/wp-login.php` is blocked at Nginx (`403`)
- Guest access to `/wp-admin` is redirected away from direct login route
- WordPress dashboard entry is provided from Laravel admin using short-lived SSO token

---

## 🕶️ WordPress Fingerprint Hardening (Active)

- `wp-fingerprint-hardening.php` MU plugin removes common WP signatures:
  - `wp_generator`, `rsd_link`, `wlwmanifest`, oEmbed discovery, shortlink, `X-Pingback`
  - public REST access returns `404` for non-authenticated users
  - asset version query (`?ver=`) is stripped
  - Elementor generator meta tag is force-disabled
- Public HTML output is obfuscated:
  - `/wp-content/` -> `/assets/`
  - `/wp-includes/` -> `/core/`
  - `/wp-admin/admin-ajax.php` -> `/ajax-endpoint`
- Nginx denies canonical public fingerprint routes for guests:
  - `/wp-json`, `/xmlrpc.php`, `/readme.html`, `/license.txt`, `/wlwmanifest.xml`
  - direct `/wp-content/`, `/wp-includes/`, `/wp-admin/admin-ajax.php`
- Known scanner user-agents (`shodan`, `censys`, `zgrab`, ...) are blocked at Nginx (`403`)

### Important Limitation
- This is **best-effort obfuscation**, not mathematically perfect stealth.
- A determined analyst can still infer WordPress with advanced behavioral probing.

---

## 🧱 Wordfence Runtime WAF (Active)

### Required Runtime Files (Production)
- `wordpress/wordfence-waf.php`
- `wordpress/.user.ini`
- `wordpress/wp-content/wflogs/`

### Runtime Requirements
- `.user.ini` must set: `auto_prepend_file = '/var/www/html/wordfence-waf.php'`
- `wp-content/wflogs/` must be writable by web user (`www-data`)
- Wordfence WAF page must not show bootstrap/config corruption warning

### Quick Verification
```bash
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ls -ld wordpress/wp-content/wflogs && ls -l wordpress/wordfence-waf.php wordpress/.user.ini"
ssh deploy@116.203.78.31 "curl -I -s https://vahidrajabloo.com/wp-login.php | head -n 1"
```

---

## 🧩 WordPress Security Plugin Stack (Active)

| Plugin | Purpose |
|--------|---------|
| Wordfence | WAF + malware scan |
| Solid Security (`better-wp-security`) | Hardening + brute-force controls |
| WP Activity Log (`wp-security-audit-log`) | Audit trail for admin/system actions |
| Fluent Forms (`fluentform`) | Business forms (kept updated for security) |

---

## ✉️ WordPress Mail Security (Active)

- WordPress SMTP is configured only via `WP_SMTP_*` environment variables.
- Hardcoded SMTP/API secrets in `wp-config.php` or theme files are prohibited.
- SMTP provider in production: Brevo (`smtp-relay.brevo.com:587`, `tls`).
- Fluent Forms notification feeds are enabled for form IDs `1` and `2`.
- Fluent Forms admin notification recipients:
  - `vahidrajablou87@gmail.com`
  - `v.rajabloo@gmail.com`

### Verification
```bash
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && docker compose exec -T wordpress php -r 'require \"/var/www/html/wp-load.php\"; var_export(wp_mail(\"v.rajabloo@gmail.com\", \"WP SMTP smoke\", \"ok\")); echo PHP_EOL;'"
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && docker compose exec -T mysql mysql -uroot -p\"$MYSQL_ROOT_PASSWORD\" -D wordpress -e \"SELECT form_id, JSON_UNQUOTE(JSON_EXTRACT(value,'$.enabled')) AS enabled FROM wp_fluentform_form_meta WHERE meta_key='notifications' ORDER BY form_id;\""
```

---

## 🛡️ Security Scripts

| Script | Purpose | Usage |
|--------|---------|-------|
| `scripts/file-monitor.sh` | Detect unauthorized file changes | `./file-monitor.sh check` |
| `scripts/deploy-log.sh` | Audit trail for deployments | Auto-runs on deploy |
| `deploy.sh` | Safe deployment (fast by default) | `./deploy.sh` |
| `scripts/weekly-full-rebuild.sh` | Weekly no-cache rebuild for patch freshness | Cron (`30 3 * * 0`) |
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

## 🔐 Laravel Auth Security (Active)

### Required Controls
- User signup requires email verification before normal panel access
- Registration forces `email_verified_at = null` on creation
- Login, registration, and password reset flows are rate limited

### Auth Rate Limits
| Flow | Limit |
|------|-------|
| Login (`/admin`, `/dashboard`) | 5 attempts / 300 seconds |
| Register (`/dashboard/register`) | 3 attempts / 600 seconds |
| Password reset request | 3 attempts / 600 seconds |
| Password reset submit | 3 attempts / 600 seconds |

### SMTP/Verification Requirements
- `MAIL_MAILER` must be `smtp` in production
- Sender identity must be verified in SMTP provider
- Domain authentication (SPF/DKIM/DMARC) is recommended for delivery

### Runtime Hard Requirements
- Laravel container DB runtime env must be sourced from `LARAVEL_DB_*` keys in server root `.env`
- `DB_PASSWORD` mismatch between container runtime and MySQL user is a critical outage risk (`HTTP 500`)
- `/var/www/laravel/storage` and `/var/www/laravel/bootstrap/cache` must stay writable by `www-data`

---

## 🌐 Edge + Origin WAF Rules

| Rule | Action |
|------|--------|
| Allow SSO `?sso=1&token=` | Skip |
| Block `/xmlrpc.php` | Block |
| Block PHP in uploads | Block |
| Block direct `/wp-login.php` | Enforced at Nginx (403) |
| Protect `/wp-admin/` | SSO/session gate + edge protection |
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

### Step 5: Audit Admin Accounts (Laravel)
```bash
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && docker exec laravel php artisan tinker --execute='print_r(\\App\\Models\\User::where(\"role\", \"admin\")->get([\"id\", \"name\", \"email\", \"created_at\"])->toArray());'"
```

### Step 6: Disable Unknown Admins (Laravel)
```bash
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && docker exec laravel php artisan tinker --execute='\\App\\Models\\User::whereIn(\"email\", [\"SUSPICIOUS_EMAIL_1\", \"SUSPICIOUS_EMAIL_2\"])->update([\"role\" => \"disabled_user\"]);'"
```

### Step 7: Force Session Logout
```bash
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && docker exec laravel php artisan tinker --execute='\\Illuminate\\Support\\Facades\\DB::table(\"sessions\")->delete();'"
```

---

## 📋 Weekly Checklist

- [ ] Run `file-monitor.sh check`
- [ ] Review deploy-history.log
- [ ] Verify weekly full rebuild log: `/var/www/vahidrajabloo-platform/logs/weekly-full-rebuild.log`
- [ ] Check Cloudflare WAF logs
- [ ] Check Wordfence WAF status is healthy (no config warning)
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
