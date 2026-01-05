# 🏗️ VahidRajabloo Platform Architecture

## 🔐 Golden Rule

> **Never mix WordPress and Laravel!**

---

## Platform Split

```
┌─────────────────────────────────────────────────────────────┐
│                     vahidrajabloo.com                        │
├─────────────────────────────┬───────────────────────────────┤
│         WordPress           │           Laravel              │
│    (Content & Marketing)    │      (Logic & Backend)         │
├─────────────────────────────┼───────────────────────────────┤
│ ✅ Landing Pages            │ ✅ User Dashboard              │
│ ✅ Blog                     │ ✅ Admin Panel                 │
│ ✅ SEO                      │ ✅ Wallet / Earnings           │
│ ✅ Store (WooCommerce)      │ ✅ Points System               │
│ ✅ Static Pages             │ ✅ Projects Management         │
│                             │ ✅ API (future)                │
│                             │ ✅ Mobile App Backend          │
└─────────────────────────────┴───────────────────────────────┘
```

---

## 🔒 User Roles (Architecture Lock)

| Role | Name | Purpose |
|------|------|---------|
| `admin` | Admin | Full system access |
| `disabled_user` | Disabled User | Receive services, support, projects |
| `family_user` | Family Disabled | Manage / support disabled user |
| `supporter_user` | Supporter | Financial support, participation, reports |

> 🔒 These roles are the **core of the system** and are defined in Laravel.

---

## 🌐 Domains

| URL | Service |
|-----|---------|
| `vahidrajabloo.com` | WordPress (Main site) |
| `app.vahidrajabloo.com` | Laravel (Dashboard & Admin) |

---

## 🗄️ Database Schema

### Users
- id, name, email, password, role
- wallet_balance (USD), points
- created_at, updated_at

### Projects
- id, user_id, title, description
- status (pending/active/completed), budget
- created_at, updated_at

### Incomes
- id, user_id, project_id, amount
- type (project/referral/bonus), description
- created_at, updated_at

### Point Transactions
- id, user_id, points
- type (earned/spent), reason, description
- created_at, updated_at

### Wallet Transactions
- id, user_id, amount
- type (deposit/withdraw), status, description
- created_at, updated_at

### Settings
- id, key, value, type, group, label
- created_at, updated_at

---

## 🎯 Admin Panel Features

| Resource | Description |
|----------|-------------|
| Users | CRUD, role management, balance/points |
| Projects | CRUD, status tracking |
| Incomes | CRUD, type filtering |
| Points | Transaction management |
| Wallet | Deposits/withdrawals |
| Settings | System configuration |
| WordPress Admin | SSO link to WordPress dashboard |

---

## 👤 User Dashboard Features

### Authentication
| Feature | Description |
|---------|-------------|
| Registration | With role selection (no admin) |
| Login | Email/password |
| Password Reset | Email-based |
| Profile | Edit personal info |

### Filament Resources (4)
| Resource | Description |
|----------|-------------|
| MyProjectResource | View user's own projects |
| MyWalletResource | View wallet transactions + balance widget |
| MyPointsResource | View points history + balance widget |
| MyIncomeResource | View income history |

---

## 🎨 Panel Branding

| Panel | Brand Name | Color |
|-------|-----------|-------|
| Admin | 🛡️ Admin Panel | Rose (Red) |
| User | 👤 My Dashboard | Blue |

---

## 🔒 Security Measures

| Measure | Status |
|---------|--------|
| UFW Firewall | ✅ Active (22, 80, 443) |
| SSL/HTTPS | ✅ Let's Encrypt |
| Cloudflare WAF | ✅ Active |
| Trusted Proxies | ✅ Active (Laravel) |
| DISALLOW_FILE_EDIT | ✅ Active |
| DISALLOW_FILE_MODS | ✅ Active |
| File Integrity Monitor | ✅ Active |
| Deploy Logging | ✅ Active |
| Rollback System | ✅ Ready |
| Daily Backups | ✅ 2am cron |
| SSL Auto-Renew | ✅ 3am cron |

---

## 💰 Currency

All monetary values are in **USD ($)**

---

## 🌐 Nginx Configuration Notes

### Laravel Server Block Requirements

**Critical:** The Laravel nginx server block must handle Livewire routes **before** static file rules:

```nginx
# Livewire routes - MUST be before static files rule
location ^~ /livewire/ {
    try_files $uri $uri/ /index.php?$query_string;
}

# Static files - browser cache (after livewire)
location ~* \.(css|js|...)$ {
    try_files $uri =404;
}
```

> ⚠️ **Without this**, Livewire.js returns 404 and login forms fail with "405 Method Not Allowed"

### Trusted Proxies (Cloudflare)

Laravel must trust Cloudflare proxies in `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->trustProxies(at: '*');
})
```

> ⚠️ **Without this**, Laravel generates `http://` URLs instead of `https://` causing Mixed Content errors

---

## 📁 Key Documentation

| Document | Purpose |
|----------|---------|
| `DEPLOYMENT.md` | How to deploy |
| `docs/ROLLBACK.md` | Emergency rollback |
| `docs/SECURITY_POLICY.md` | Security guidelines |
| `docs/PRE_DEPLOY_CHECKLIST.md` | Deploy checklist |

