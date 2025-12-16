# 🔄 Rollback System

## 🔐 Rollback Golden Rules

```
1. Rollback reverts CODE ONLY (Git-tracked files)
2. Rollback takes 1-3 minutes maximum
3. Rollback is SAFE to run multiple times
4. Rollback uses Git, NOT backups
5. Rollback NEVER touches:
   ❌ MySQL databases (WordPress or Laravel)
   ❌ wp-content/uploads
   ❌ wp-config.php
   ❌ .env files
```

---

## 🚀 Quick Reference

```bash
# Interactive mode (shows recent commits, pick one)
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh"

# Direct rollback to specific commit
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh abc123"

# Dry-run (simulation, no changes)
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh --dry-run"
```

---

## ✅ When to Use Rollback

| Situation | Use Rollback? |
|-----------|--------------|
| Broken theme after deploy | ✅ YES |
| PHP fatal error after deploy | ✅ YES |
| CSS/JS broken after deploy | ✅ YES |
| Plugin code causing 500 error | ✅ YES |
| Laravel route not working | ✅ YES |
| Need previous version quickly | ✅ YES |

---

## ❌ When NOT to Use Rollback

| Situation | Why Not | Use Instead |
|-----------|---------|-------------|
| Database corrupted | Rollback = CODE only | Restore from backup |
| Uploads deleted | Rollback = CODE only | Restore from backup |
| Wrong Elementor content | Not in Git | Recreate in WP admin |
| Need to restore old posts | Not in Git | Restore DB backup |
| wp-config.php broken | Not in Git | Manual SSH edit |

---

## 📋 How Rollback Works

```
┌─────────────────────────────────────────────────────────┐
│                    ROLLBACK FLOW                         │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  1. Show recent commits                                  │
│  2. User selects target commit                           │
│  3. git checkout <commit> -- .                           │
│  4. docker compose build --no-cache                      │
│  5. docker compose up -d                                 │
│  6. Health check                                         │
│                                                          │
│  ⏱️ Total time: 1-3 minutes                              │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

## 🧪 Testing Rollback Safely

### Step 1: Dry Run
```bash
# See what would change without making changes
./rollback.sh --dry-run
```

### Step 2: Check the Output
```
📋 Files that will change:
 laravel/app/Providers/...  | 10 +++---
 wordpress/wp-content/...   | 5 ++--
 2 files changed, 8 insertions(+), 7 deletions(-)
```

### Step 3: If Safe, Execute
```bash
./rollback.sh abc123
```

---

## 🔧 Rollback Script Features

| Feature | Description |
|---------|-------------|
| `--dry-run` | Simulation mode, no changes |
| Interactive | Lists commits, prompts for selection |
| Direct | Pass commit hash as argument |
| Color output | Easy to read success/error |
| Prerequisites check | Validates Git and Docker |
| Health check | Confirms services running |
| Undo instructions | Shows how to revert the rollback |

---

## 🚨 Emergency Rollback

If site is completely broken:

```bash
# SSH to server
ssh root@116.203.78.31

# Go to project
cd /var/www/vahidrajabloo-platform

# Quick rollback to previous commit
git checkout HEAD~1 -- .
docker compose up -d

# If more rollback needed
./rollback.sh
```

---

## ⚠️ Rollback Limitations

| What CAN be rolled back | What CANNOT be rolled back |
|------------------------|---------------------------|
| PHP code | Database content |
| Theme files | WordPress posts/pages |
| Plugin code | Elementor designs |
| CSS/JavaScript | User uploads |
| Laravel routes | Settings stored in DB |
| Filament resources | Menu configurations |

---

## 📊 Rollback vs Restore

| Action | Speed | Scope | Risk |
|--------|-------|-------|------|
| **Rollback** | 1-3 min | CODE only | Very Low |
| **DB Restore** | 5-15 min | Database | Medium |
| **Full Restore** | 15-30 min | Everything | Higher |

> **Rule**: Always try rollback FIRST. Only restore backups if database is corrupted.
