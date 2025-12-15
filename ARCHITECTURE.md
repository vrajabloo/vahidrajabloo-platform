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

---

## 👤 User Dashboard Features

| Feature | Description |
|---------|-------------|
| Registration | With role selection (no admin) |
| Login | Email/password |
| Password Reset | Email-based |
| Profile | Edit personal info |
| Welcome Widget | Personalized greeting |

---

## 🔒 Security Measures

| Measure | Status |
|---------|--------|
| UFW Firewall | ✅ Active (22, 80, 443) |
| SSL/HTTPS | ✅ Let's Encrypt |
| Strong Passwords | ✅ 32 characters |
| Rate Limiting | ✅ Nginx config |
| Blocked .env/.git | ✅ Nginx config |
| Daily Backups | ✅ 2am cron |
| SSL Auto-Renew | ✅ 3am cron |

---

## 💰 Currency

All monetary values are in **USD ($)**
