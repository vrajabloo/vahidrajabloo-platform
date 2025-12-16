# 🧪 Staging Environment Policy

## Purpose
Staging is a pre-production environment used to test CODE changes safely
before deploying them to Production.

---

## Environment Rules

| Environment | Purpose | Database |
|------------|--------|----------|
| Local | Development & prototyping | Local DB |
| Staging | Pre-production testing | Cloned / masked |
| Production | Live users & real data | Sacred |

---

## Golden Rules

- ❌ Production database is NEVER overwritten
- ❌ Local database is NEVER pushed to Production
- ✅ Only CODE is deployed via Git
- ⚠️ Staging database may be refreshed ONLY from Production (one-way)

---

## Allowed Operations

### Local → Staging
- Deploy theme / plugin code
- Test UI, layout, performance

### Staging → Production
- Deploy code ONLY
- No database operations

---

## Forbidden Operations

- ❌ Local → Production DB sync
- ❌ Deploy scripts touching MySQL
- ❌ Elementor editing in Local expecting Production update

---

## Elementor Usage

- Elementor editing is allowed in:
  - Production (real content)
  - Staging (testing only)
- Elementor changes in Staging must be manually recreated in Production
  or selectively exported/imported.

---

## Summary

> Staging exists to protect Production,
> not to shortcut content workflows.
