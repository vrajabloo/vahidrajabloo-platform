#!/bin/bash

# ===========================================
# 🔒 Security Integrity Check
# ===========================================
#
# PURPOSE: Verify file integrity and detect unauthorized changes
#
# USAGE:
#   ./security-check.sh           # Full check
#   ./security-check.sh --quick   # Quick check (no core verify)
#
# ===========================================

set -euo pipefail

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Functions
log_info() { echo -e "${BLUE}[INFO]${NC} $1"; }
log_success() { echo -e "${GREEN}[✓]${NC} $1"; }
log_warning() { echo -e "${YELLOW}[⚠]${NC} $1"; }
log_error() { echo -e "${RED}[✗]${NC} $1"; }

QUICK_MODE=false
ISSUES_FOUND=0

# Parse arguments
if [[ "${1:-}" == "--quick" ]]; then
    QUICK_MODE=true
fi

echo ""
echo "==========================================="
echo "🔒 Security Integrity Check"
echo "==========================================="
echo ""

# -----------------------------------------
# Check 1: Git status (should be clean)
# -----------------------------------------
log_info "Checking Git status..."
if [ -n "$(git status --porcelain)" ]; then
    log_error "Uncommitted changes detected!"
    echo ""
    git status --short
    echo ""
    ((ISSUES_FOUND++))
else
    log_success "Git status clean"
fi

# -----------------------------------------
# Check 2: mu-plugins match Git
# -----------------------------------------
log_info "Checking mu-plugins integrity..."
MU_DIFF=$(git diff wordpress/wp-content/mu-plugins/ 2>/dev/null || true)
if [ -n "$MU_DIFF" ]; then
    log_error "mu-plugins have been modified!"
    echo "$MU_DIFF"
    ((ISSUES_FOUND++))
else
    log_success "mu-plugins match Git"
fi

# -----------------------------------------
# Check 3: Unknown files in wp-content
# -----------------------------------------
log_info "Checking for unknown files..."
UNKNOWN_FILES=$(git ls-files --others --exclude-standard wordpress/wp-content/ 2>/dev/null || true)
if [ -n "$UNKNOWN_FILES" ]; then
    log_warning "Unknown files found (not in Git):"
    echo "$UNKNOWN_FILES"
else
    log_success "No unknown files in wp-content"
fi

# -----------------------------------------
# Check 4: WordPress core checksums (slow)
# -----------------------------------------
if [ "$QUICK_MODE" = false ]; then
    log_info "Verifying WordPress core checksums..."
    if docker exec wordpress wp core verify-checksums --allow-root 2>/dev/null; then
        log_success "WordPress core files verified"
    else
        log_error "WordPress core files MODIFIED!"
        ((ISSUES_FOUND++))
    fi
else
    log_info "Skipping core verify (quick mode)"
fi

# -----------------------------------------
# Check 5: Critical SSO files
# -----------------------------------------
log_info "Checking SSO critical files..."

SSO_FILES=(
    "laravel/app/Models/WpLoginToken.php"
    "laravel/app/Http/Controllers/WpAutoLoginController.php"
    "wordpress/wp-content/mu-plugins/laravel-sso.php"
)

for file in "${SSO_FILES[@]}"; do
    if [ -f "$file" ]; then
        DIFF=$(git diff "$file" 2>/dev/null || true)
        if [ -n "$DIFF" ]; then
            log_error "SSO file modified: $file"
            ((ISSUES_FOUND++))
        else
            log_success "$file OK"
        fi
    else
        log_error "SSO file missing: $file"
        ((ISSUES_FOUND++))
    fi
done

# -----------------------------------------
# Summary
# -----------------------------------------
echo ""
echo "==========================================="
if [ $ISSUES_FOUND -eq 0 ]; then
    echo -e "${GREEN}✅ ALL CHECKS PASSED${NC}"
else
    echo -e "${RED}❌ $ISSUES_FOUND ISSUE(S) FOUND${NC}"
    echo ""
    echo "Actions:"
    echo "  1. Review the issues above"
    echo "  2. If unauthorized: git checkout -- ."
    echo "  3. If intentional: git add && git commit"
fi
echo "==========================================="
echo ""

exit $ISSUES_FOUND
