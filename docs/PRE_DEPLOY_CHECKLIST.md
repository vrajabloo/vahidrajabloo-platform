# ✅ Pre-Deploy Checklist

## Before Every Deploy

### Code Safety
- [ ] No `.env` changes included
- [ ] No `wp-config.php` committed
- [ ] No secrets added to Git
- [ ] No changes in `wp-content/uploads`

---

### Database Safety
- [ ] `deploy.sh` does NOT touch MySQL
- [ ] No `docker compose down -v`
- [ ] No migration affecting WordPress DB

---

### Elementor Safety
- [ ] No expectation of Elementor sync
- [ ] Elementor changes done directly in Production
- [ ] Any needed page exported manually

---

### Infrastructure
- [ ] `git pull` only (no force)
- [ ] Docker builds successfully
- [ ] Containers start without error
- [ ] Nginx returns 200 OK

---

### Backup
- [ ] Daily backup exists
- [ ] Manual backup taken if risky deploy

---

## Final Rule

> If a deploy can delete data,
> it is NOT a deploy — it is an incident.
