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
# Interactive mode (shows recent commits, pick by number or hash)
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh"

# Direct rollback to specific commit
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh abc123"

# Dry-run (simulation, NO changes made)
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh --dry-run abc123"

# Force full Docker rebuild (slower but cleaner)
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh --no-cache abc123"

# Override dirty working tree check (dangerous!)
ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./rollback.sh --force abc123"
```

---

## 🎛️ Command Line Flags

| Flag | Default | Description |
|------|---------|-------------|
| `--dry-run` | Off | Simulation mode - shows commands without executing |
| `--no-cache` | Off | Forces `docker build --no-cache` (slower, ~5 min) |
| `--force` | Off | Overrides dirty working tree check (dangerous!) |
| `--help` | - | Shows usage information |

### Flag Details

#### `--dry-run`
Shows exactly what would happen without making any changes:
- No `git checkout`
- No Docker restart
- Prints the commands that would run

```bash
./rollback.sh --dry-run abc123
# Output:
# Commands that would run:
#   git checkout abc123 -- .
#   docker compose up -d --build
```

#### `--no-cache`
By default, rollback uses `docker compose up -d --build` (fast, ~1 min).
With `--no-cache`, it uses:
- `docker compose build --no-cache` (clean rebuild)
- `docker compose up -d`

Use when: Docker cache might be corrupted or you need a clean slate.

#### `--force`
By default, rollback **aborts if there are uncommitted changes** in the working tree.
This protects against accidental data loss.

With `--force`, the rollback proceeds anyway (changes will be LOST).

---

## 🔍 Health Checks

After rollback, the script automatically checks:

### 1. Container Status
```
📦 Container Status:
NAME        STATUS
nginx       Up 5 seconds
wordpress   Up 5 seconds
mysql       Up 5 seconds
laravel     Up 5 seconds
```

### 2. HTTP Endpoints
```
🌐 HTTP Endpoints:
  ✅ https://vahidrajabloo.com → HTTP 200
  ✅ https://app.vahidrajabloo.com → HTTP 200
```

### Health Check Failure
If health check fails, the script:
1. Prints a **FAILED** message
2. Provides the **undo command**
3. Exits with non-zero code

```bash
❌ ROLLBACK COMPLETED BUT HEALTH CHECK FAILED

Recommended actions:
  1. Check logs: docker compose logs -f
  2. Manual restart: docker compose restart
  3. Undo rollback: ./rollback.sh abc123

🔙 To UNDO this rollback:
   ./rollback.sh abc123
```

### Customizing Health Check Endpoints
Edit the `HEALTH_ENDPOINTS` array in `rollback.sh`:
```bash
HEALTH_ENDPOINTS=(
    "https://vahidrajabloo.com"
    "https://app.vahidrajabloo.com"
    "https://api.example.com/health"  # Add more
)
```

---

## 🚫 Dirty Working Tree Protection

By default, rollback **aborts** if there are uncommitted changes:

```
❌ Dirty working tree detected!

Uncommitted changes:
 M docker/nginx/default.conf
 M laravel/routes/web.php

Options:
  1. Commit your changes first
  2. Discard changes: git checkout -- .
  3. Use --force flag to override (dangerous)
```

This prevents:
- Accidental loss of work-in-progress
- Confusion about what's deployed
- Violation of "never edit on server" rule

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
│  1. Check prerequisites (git, docker, clean tree)        │
│  2. Show recent commits (interactive mode)               │
│  3. Capture current commit (for undo)                    │
│  4. git checkout <target> -- .                           │
│  5. docker compose up -d --build                         │
│     OR docker compose build --no-cache (if --no-cache)   │
│  6. Health check (containers + HTTP)                     │
│  7. Print undo command                                   │
│                                                          │
│  ⏱️ Default: 1-3 minutes                                 │
│  ⏱️ With --no-cache: 3-5 minutes                         │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

## 🧪 Testing Rollback Safely

### Step 1: Always Start with Dry Run
```bash
./rollback.sh --dry-run abc123
```

### Step 2: Review the Output
```
Commands that would run:
  git checkout abc123 -- .
  docker compose up -d --build

📋 Files that will change:
 laravel/app/Providers/...  | 10 +++---
 wordpress/wp-content/...   | 5 ++--
 2 files changed, 8 insertions(+), 7 deletions(-)
```

### Step 3: If Safe, Execute
```bash
./rollback.sh abc123
```

### Step 4: Note the Undo Command
The script prints this at the end:
```
🔙 To UNDO this rollback:
   ./rollback.sh def456
```

---

## 🚨 Emergency Rollback

If site is completely broken:

```bash
# SSH to server
ssh root@116.203.78.31

# Go to project
cd /var/www/vahidrajabloo-platform

# Quick rollback to previous commit
./rollback.sh

# Or direct to specific commit
./rollback.sh abc123

# If Docker cache is corrupted
./rollback.sh --no-cache abc123
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
| **Rollback --no-cache** | 3-5 min | CODE only | Very Low |
| **DB Restore** | 5-15 min | Database | Medium |
| **Full Restore** | 15-30 min | Everything | Higher |

> **Rule**: Always try rollback FIRST. Only restore backups if database is corrupted.

---

## 🔧 Exit Codes

| Code | Meaning |
|------|---------|
| 0 | Success |
| 1 | Error (prerequisites failed, invalid commit, health check failed) |

Scripts and automation can rely on these exit codes.
