---
description: How to deploy changes to production server
---

# 📋 Deployment Workflow

## 🔐 Golden Rule
```
❌ هیچ کدی مستقیم روی سرور ویرایش نشود
✅ فقط GitHub → deploy.sh
✅ از یوزر deploy@ استفاده کنید (نه root@)
```

---

## 📦 Deployment Steps

### 1. Make Changes Locally
```bash
cd "/Users/Data/Desktop/My Site/vahidrajabloo-platform"

# Test locally with Docker
docker compose -f docker-compose.local.yml up -d

# Check at http://localhost:8080
```

### 2. Commit & Push to GitHub
```bash
git add .
git commit -m "description of changes"
git push origin main
```

// turbo
### 3. Deploy to Server
```bash
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"
```

---

## 🔄 Quick Deploy (One Command)
```bash
git add . && git commit -m "update" && git push && ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"
```

---

## 🚨 Emergency Rollback
```bash
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh"
```

Or to specific commit:
```bash
ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh abc123"
```

---

## ⚠️ Never Do This
- ❌ Edit files directly on server via SSH
- ❌ FTP/SCP single files to server
- ❌ Change database content without backup
- ❌ Run `docker compose down -v` (deletes data!)
- ❌ Use root@ for regular deployments
