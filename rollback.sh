#!/bin/bash

# ===========================================
# 🔄 VahidRajabloo Platform - Production Rollback
# ===========================================
#
# PURPOSE: Rollback CODE ONLY to a previous Git commit
#
# SACRED DATA (NEVER TOUCHED):
#   ❌ MySQL databases
#   ❌ wp-content/uploads
#   ❌ wp-config.php
#   ❌ .env files
#
# USAGE:
#   ./rollback.sh              # Interactive mode
#   ./rollback.sh abc123       # Direct rollback to commit
#   ./rollback.sh --dry-run    # Simulation mode
#
# ===========================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Configuration
DRY_RUN=false
TARGET_COMMIT=""
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"

# Functions
log_info() { echo -e "${BLUE}[INFO]${NC} $1"; }
log_success() { echo -e "${GREEN}[SUCCESS]${NC} $1"; }
log_warning() { echo -e "${YELLOW}[WARNING]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }

show_header() {
    echo ""
    echo "==========================================="
    echo "🔄 VahidRajabloo Platform - Rollback"
    echo "==========================================="
    echo ""
}

show_golden_rules() {
    echo -e "${YELLOW}🔐 ROLLBACK GOLDEN RULES:${NC}"
    echo "   ✅ CODE ONLY - themes, plugins, PHP/CSS/JS"
    echo "   ❌ NEVER touches MySQL databases"
    echo "   ❌ NEVER touches wp-content/uploads"
    echo "   ❌ NEVER touches wp-config.php or .env"
    echo ""
}

check_prerequisites() {
    log_info "Checking prerequisites..."
    
    # Check if git repository
    if [ ! -d ".git" ]; then
        log_error "Not a git repository!"
        exit 1
    fi
    
    # Check if docker is running
    if ! docker info > /dev/null 2>&1; then
        log_error "Docker is not running!"
        exit 1
    fi
    
    log_success "Prerequisites OK"
}

show_recent_commits() {
    echo ""
    echo -e "${BLUE}📜 Recent Commits (last 10):${NC}"
    echo "─────────────────────────────────────────────────"
    git log --oneline -10 --format="%h  %s  (%cr)"
    echo "─────────────────────────────────────────────────"
    echo ""
}

get_current_commit() {
    git rev-parse --short HEAD
}

validate_commit() {
    local commit=$1
    if git cat-file -t "$commit" > /dev/null 2>&1; then
        return 0
    else
        return 1
    fi
}

perform_rollback() {
    local commit=$1
    local current=$(get_current_commit)
    
    echo ""
    log_info "Current commit: $current"
    log_info "Target commit:  $commit"
    echo ""
    
    # Show what will change
    echo -e "${BLUE}📋 Files that will change:${NC}"
    git diff --stat "$commit" HEAD 2>/dev/null | tail -20
    echo ""
    
    if [ "$DRY_RUN" = true ]; then
        log_warning "DRY RUN MODE - No changes made"
        echo ""
        echo "To perform actual rollback, run:"
        echo "  ./rollback.sh $commit"
        return 0
    fi
    
    # Confirmation
    echo -e "${YELLOW}⚠️  WARNING: This will rollback CODE to commit $commit${NC}"
    read -p "Continue? (y/N): " confirm
    if [ "$confirm" != "y" ] && [ "$confirm" != "Y" ]; then
        log_info "Rollback cancelled."
        exit 0
    fi
    
    # Step 1: Stash any local changes (shouldn't be any)
    log_info "[1/4] Checking for local changes..."
    if [ -n "$(git status --porcelain)" ]; then
        log_warning "Local changes detected, stashing..."
        git stash
    fi
    
    # Step 2: Checkout the target commit
    log_info "[2/4] Rolling back to commit $commit..."
    git checkout "$commit" -- .
    
    # Step 3: Rebuild containers (without touching volumes)
    log_info "[3/4] Rebuilding Docker containers..."
    docker compose build --no-cache
    
    # Step 4: Restart services
    log_info "[4/4] Restarting services..."
    docker compose up -d
    
    # Wait for services
    log_info "Waiting for services to start..."
    sleep 5
    
    # Health check
    echo ""
    if docker ps | grep -q nginx && docker ps | grep -q wordpress && docker ps | grep -q mysql; then
        log_success "All services running!"
    else
        log_error "Some services may have failed. Check with: docker ps"
    fi
    
    echo ""
    echo "==========================================="
    echo -e "${GREEN}✅ ROLLBACK COMPLETE${NC}"
    echo "==========================================="
    echo ""
    echo "Rolled back from: $current"
    echo "Rolled back to:   $commit"
    echo ""
    echo "Verify:"
    echo "  🌐 https://vahidrajabloo.com"
    echo "  🌐 https://app.vahidrajabloo.com"
    echo ""
    echo "To undo this rollback:"
    echo "  git checkout $current -- ."
    echo "  docker compose up -d"
    echo ""
}

# ===========================================
# MAIN
# ===========================================

cd "$SCRIPT_DIR"

# Parse arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        --dry-run)
            DRY_RUN=true
            shift
            ;;
        --help|-h)
            show_header
            echo "Usage: ./rollback.sh [OPTIONS] [COMMIT_HASH]"
            echo ""
            echo "Options:"
            echo "  --dry-run    Simulation mode, no changes"
            echo "  --help       Show this help"
            echo ""
            echo "Examples:"
            echo "  ./rollback.sh              # Interactive"
            echo "  ./rollback.sh abc123       # Direct rollback"
            echo "  ./rollback.sh --dry-run    # Show changes only"
            exit 0
            ;;
        *)
            TARGET_COMMIT=$1
            shift
            ;;
    esac
done

show_header
show_golden_rules
check_prerequisites

if [ "$DRY_RUN" = true ]; then
    log_warning "DRY RUN MODE ENABLED"
    echo ""
fi

# If no commit specified, interactive mode
if [ -z "$TARGET_COMMIT" ]; then
    show_recent_commits
    read -p "Enter commit hash to rollback to: " TARGET_COMMIT
    
    if [ -z "$TARGET_COMMIT" ]; then
        log_error "No commit specified!"
        exit 1
    fi
fi

# Validate commit
if ! validate_commit "$TARGET_COMMIT"; then
    log_error "Invalid commit: $TARGET_COMMIT"
    exit 1
fi

# Perform rollback
perform_rollback "$TARGET_COMMIT"
