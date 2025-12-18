# 🚀 Deployment Guide

**Last Updated:** 2025-12-18

## 🔐 Golden Rule

```
❌ Never edit code directly on server
✅ Only GitHub → deploy.sh
✅ Every deploy is logged
```

---

## 📋 Quick Reference

| Action | Command |
|--------|---------|
| Deploy | `ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"` |
| Rollback | `ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh"` |
| View logs | `ssh root@116.203.78.31 "docker logs nginx --tail 50"` |
| Status | `ssh root@116.203.78.31 "docker ps"` |
| File check | `ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./scripts/file-monitor.sh check"` |

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
cd ~/Desktop/My\ Web\ Site/vahidrajabloo-platform
# Edit theme/plugin files
git add .
git commit -m "feat: description"
git push origin main
```

### Step 2: Deploy
```bash
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"
```

### Step 3: Verify
- Website: https://vahidrajabloo.com
- Admin: https://vahidrajabloo.com/wp-admin/
- SSO: From Laravel admin panel

---

## 🔄 Rollback (Emergency)

```bash
# Interactive mode
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh"

# Direct to specific commit
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh abc123"

# Dry run first
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh --dry-run"
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
```
