# 📋 Deployment Workflow

## 🔐 Golden Rule
```
❌ هیچ کدی مستقیم روی سرور ویرایش نشود
✅ فقط GitHub → deploy.sh
```

---

## 📦 Deployment Steps

### 1. Make Changes Locally
```bash
# Edit files on your Mac
# Test locally with Docker
docker-compose up -d
```

### 2. Commit & Push to GitHub
```bash
git add .
git commit -m "description of changes"
git push origin main
```

### 3. Deploy to Server
```bash
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"
```

Or run from local:
```bash
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && git pull && docker compose up -d --build"
```

---

## 🚨 Emergency Rollback
```bash
ssh root@116.203.78.31
cd /var/www/vahidrajabloo-platform
git log --oneline -5              # Find previous commit
git checkout <commit-hash> -- .   # Rollback files
docker compose up -d --build      # Rebuild
```

---

## ⚠️ Never Do This
- ❌ Edit files directly on server via SSH
- ❌ FTP/SCP single files to server
- ❌ Change database content without backup
- ❌ Run `docker compose down -v` (deletes data!)
