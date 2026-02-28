# ✅ Pre-Deploy Checklist

**Last Updated:** 2026-02-28

## Before Every Deploy

### 🔒 Security Checks
- [ ] No `.env` changes included
- [ ] No `wp-config.php` committed
- [ ] No secrets/passwords in Git
- [ ] No files in `wp-content/uploads` tracked
- [ ] No hardcoded SMTP/API secrets in `wp-config.php` or theme files
- [ ] Theme version updated in `functions.php`
- [ ] Auth page classes are still custom (not reverted to Filament defaults)
- [ ] Rate limits are active for login/register/password reset
- [ ] `wp-login.php` is still blocked by Nginx (HTTP 403 expected)
- [ ] Wordfence runtime WAF files exist: `wordfence-waf.php`, `.user.ini`, `wp-content/wflogs/`
- [ ] Security plugins active: Wordfence, Solid Security, WP Activity Log

---

### 🛡️ File Safety
- [ ] `scripts/file-monitor.sh baseline` will be updated
- [ ] No unknown PHP files in commit
- [ ] Only tracked directories modified:
  - `wordpress/wp-content/themes/vahidrajabloo-theme/`
  - `wordpress/wp-content/mu-plugins/`
  - `laravel/`
  - `scripts/`
  - `docs/`

---

### 💾 Database Safety
- [ ] `deploy.sh` does NOT touch MySQL
- [ ] No `docker compose down -v` (deletes data!)
- [ ] No destructive migrations
- [ ] Server `.env` includes valid `LARAVEL_DB_*` values (source of truth for laravel container DB env)

---

### 🏗️ Infrastructure
- [ ] `git pull` only (no force)
- [ ] Fast deploy is enough for this change (`./deploy.sh`)
- [ ] If this is weekly maintenance, run full rebuild (`./deploy.sh --full-rebuild`)
- [ ] Docker build strategy selected correctly (cached vs full rebuild)
- [ ] Containers start without error
- [ ] Health checks pass (HTTP 200)
- [ ] If `WP_SMTP_*` changed, WordPress container will be recreated
- [ ] `laravel` container runtime DB env matches expected values (`docker compose exec -T laravel env | grep ^DB_`)

---

### 📦 Backup
- [ ] Daily backup exists
- [ ] Manual backup taken if risky deploy

---

## Deploy Command

```bash
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"
```

---

## Post-Deploy Verification

- [ ] Website loads: https://vahidrajabloo.com
- [ ] Admin works: https://vahidrajabloo.com/wp-admin/
- [ ] App login works: https://app.vahidrajabloo.com/dashboard/login
- [ ] SSO works from Laravel admin
- [ ] User signup requires email verification
- [ ] Verification email is received
- [ ] Password reset email is received
- [ ] WordPress SMTP smoke test (`wp_mail`) returns `true`
- [ ] Fluent Forms notification feed remains enabled (form IDs 1 and 2)
- [ ] Login rate limit triggers after repeated failed attempts
- [ ] `https://vahidrajabloo.com/wp-login.php` returns 403
- [ ] `https://vahidrajabloo.com/wp-json` returns 404
- [ ] Wordfence WAF page has no bootstrap/config corruption warning
- [ ] WordPress admin remains reachable via Laravel SSO flow
- [ ] Check `deploy-history.log` for entry
- [ ] Verify `file-monitor.sh baseline` updated
- [ ] `storage` and `bootstrap/cache` are writable by `www-data`

---

## 🚨 Final Rule

> If a deploy can delete data,
> it is NOT a deploy — it is an incident.
