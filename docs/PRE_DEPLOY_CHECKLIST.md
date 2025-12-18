# ✅ Pre-Deploy Checklist

**Last Updated:** 2025-12-18

## Before Every Deploy

### 🔒 Security Checks
- [ ] No `.env` changes included
- [ ] No `wp-config.php` committed
- [ ] No secrets/passwords in Git
- [ ] No files in `wp-content/uploads` tracked
- [ ] Theme version updated in `functions.php`

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

---

### 🏗️ Infrastructure
- [ ] `git pull` only (no force)
- [ ] Docker builds successfully
- [ ] Containers start without error
- [ ] Health checks pass (HTTP 200)

---

### 📦 Backup
- [ ] Daily backup exists
- [ ] Manual backup taken if risky deploy

---

## Deploy Command

```bash
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"
```

---

## Post-Deploy Verification

- [ ] Website loads: https://vahidrajabloo.com
- [ ] Admin works: https://vahidrajabloo.com/wp-admin/
- [ ] SSO works from Laravel admin
- [ ] Check `deploy-history.log` for entry
- [ ] Verify `file-monitor.sh baseline` updated

---

## 🚨 Final Rule

> If a deploy can delete data,
> it is NOT a deploy — it is an incident.
