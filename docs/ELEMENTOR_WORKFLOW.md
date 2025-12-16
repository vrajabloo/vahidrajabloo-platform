# 🎨 Elementor Content Workflow

## Core Principle

> Elementor content is DATA, not CODE.

Therefore:
- Git does not track it
- Deploy does not move it

---

## Where Elementor Should Be Used

| Environment | Elementor Usage |
|-----------|----------------|
| Local | ❌ Prototype only |
| Staging | ⚠️ Testing |
| Production | ✅ Real content |

---

## Correct Workflow

### UI / Layout Development
1. Build layout idea in Local (optional)
2. Implement reusable parts in Theme (PHP/CSS)
3. Deploy theme code
4. Create final pages in Production with Elementor

---

### Creating Pages
- Pages MUST be created directly in Production
- All marketing, landing, and SEO pages live only in Production

---

## Transferring Elementor Content (If Needed)

### Allowed Methods
- Elementor → Export single page / template
- Import manually in Production

### Forbidden Methods
- ❌ Database sync
- ❌ mysqldump
- ❌ Deploy-based migration

---

## Safe Mindset

> If it was built with Elementor,
> assume it lives ONLY in Production.
