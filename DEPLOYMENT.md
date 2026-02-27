# 🚀 Deployment Guide

**Last Updated:** 2026-02-27

## 🔐 Golden Rule

```
❌ Never edit code directly on server
✅ Only GitHub → deploy.sh
✅ Every deploy is logged
✅ Use deploy@ user (not root)
```

---

## 📋 Quick Reference

| Action | Command |
|--------|---------|
| Deploy | `ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"` |
| Rollback | `ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh"` |
| View logs | `ssh deploy@116.203.78.31 "docker logs nginx --tail 50"` |
| Status | `ssh deploy@116.203.78.31 "docker ps"` |
| File check | `ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./scripts/file-monitor.sh check"` |

---

## 👤 SSH Users

| User | Purpose | Usage |
|------|---------|-------|
| `deploy` | Regular deployments | ✅ Recommended |
| `root` | Emergency only | ⚠️ Use sparingly |

```bash
# Recommended
ssh deploy@116.203.78.31

# Emergency only
ssh root@116.203.78.31 (emergency)
```

---

## 📦 Deployment Flow

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   LOCAL     │────▶│   GITHUB    │────▶│   SERVER    │
│   Edit      │     │   Push      │     │  deploy.sh  │
└─────────────┘     └─────────────┘     └─────────────┘
                                               │
                                               ▼
                                        ┌─────────────┐
                                        │  LOGGED     │
                                        │  BASELINED  │
                                        └─────────────┘
```

### Step 1: Make Changes (Local)
```bash
cd "/Users/Data/Desktop/My Site/vahidrajabloo-platform"

# Test locally first
docker compose -f docker-compose.local.yml up -d

# Check at http://localhost:8080

# Commit changes
git add .
git commit -m "feat: description"
git push origin main
```

### Step 2: Deploy
```bash
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"
```

Notes:
- `deploy.sh` blocks only tracked Git changes.
- Runtime untracked files (for example WordPress plugin files installed from wp-admin) do not block deployment.

### Step 3: Verify
- Website: https://vahidrajabloo.com
- Admin: https://vahidrajabloo.com/wp-admin/
- App User Panel: https://app.vahidrajabloo.com/dashboard
- App Admin Panel: https://app.vahidrajabloo.com/admin
- SSO: From Laravel admin panel
- Direct WordPress login URL (`/wp-login.php`) returns `403`

### Step 4: Auth & Email Smoke Test
- [ ] Register a new user at `/dashboard/register`
- [ ] Confirm user is redirected to email verification screen
- [ ] Confirm verification email is received and link works
- [ ] Confirm unverified user cannot access protected panel pages
- [ ] Confirm login rate limit triggers after repeated failures
- [ ] Confirm password reset email is received

### Step 5: If `MAIL_*` or `WP_SMTP_*` Changed
```bash
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && docker exec laravel php artisan optimize:clear"
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && docker compose up -d --no-deps --force-recreate wordpress"
```

Then retry:
- Register verification email
- Password reset email
- WordPress SMTP smoke test

### Step 6: WordPress SMTP Smoke Test
```bash
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && docker compose exec -T wordpress php -r 'require \"/var/www/html/wp-load.php\"; var_export(wp_mail(\"v.rajabloo@gmail.com\", \"WP SMTP smoke\", \"ok\")); echo PHP_EOL;'"
```

Expected result: `true`

### Step 7: WordPress Security Smoke Test
- [ ] Open Wordfence panel and confirm no WAF bootstrap/config error
- [ ] Confirm security plugins are active: Wordfence, Solid Security, WP Activity Log
- [ ] Confirm Laravel admin SSO still opens WordPress dashboard
- [ ] Confirm `https://vahidrajabloo.com/wp-json` returns `404`
- [ ] Confirm direct `https://vahidrajabloo.com/wp-admin/admin-ajax.php` returns `404`

---

## 🔄 Rollback (Emergency)

```bash
# Interactive mode
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh"

# Direct to specific commit
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh abc123"

# Dry run first
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh --dry-run"
```

---

## 🛡️ Security Features

| Feature | Status |
|---------|--------|
| DISALLOW_FILE_EDIT | ✅ Active |
| DISALLOW_FILE_MODS | ✅ Active |
| FORCE_SSL_ADMIN | ✅ Active |
| Deploy logging | ✅ Active |
| File integrity monitor | ✅ Active |
| Cloudflare WAF | ✅ Active |
| Non-root deploy user | ✅ Active |
| Direct `/wp-login.php` blocked | ✅ Active |
| Laravel SSO-only WP admin entry | ✅ Active |
| Wordfence runtime WAF bootstrap | ✅ Active |
| WordPress security plugins | ✅ Active |
| WP SMTP via env (`WP_SMTP_*`) | ✅ Active |
| Fluent Forms notification feeds enabled | ✅ Active |

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
| SSH User | deploy (recommended) |

---

## 🗂️ What Syncs Where

| Item | Git | Database |
|------|-----|----------|
| Theme files | ✅ | |
| mu-plugins | ✅ | |
| Security scripts | ✅ | |
| Posts/Pages | | ✅ |
| Settings | | ✅ |
| Uploads | ❌ | |

---

## ⚠️ Never Do

```
❌ Edit files directly on server
❌ Run docker compose down -v
❌ Install plugins via wp-admin
❌ Expose database port
❌ Skip rollback.sh for emergencies
❌ Use root@ for regular deployments
```
