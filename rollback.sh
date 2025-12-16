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
#   ./rollback.sh --dry-run    # Simulation mode (no changes)
#   ./rollback.sh --no-cache   # Force full rebuild
#   ./rollback.sh --force      # Override dirty working tree check
#
# ===========================================

set -euo pipefail

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Configuration
DRY_RUN=false
NO_CACHE=false
FORCE=false
TARGET_COMMIT=""
CURRENT_COMMIT=""
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"

# Health check endpoints
HEALTH_ENDPOINTS=(
    "https://vahidrajabloo.com"
    "https://app.vahidrajabloo.com"
)

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
    
    # Check for dirty working tree
    if [ -n "$(git status --porcelain)" ]; then
        echo ""
        log_error "Dirty working tree detected!"
        echo ""
        echo "Uncommitted changes:"
        git status --short
        echo ""
        if [ "$FORCE" = true ]; then
            log_warning "Proceeding anyway due to --force flag"
            log_warning "Changes will be LOST after rollback!"
        else
            echo "Options:"
            echo "  1. Commit your changes first"
            echo "  2. Discard changes: git checkout -- ."
            echo "  3. Use --force flag to override (dangerous)"
            echo ""
            exit 1
        fi
    fi
    
    log_success "Prerequisites OK"
}

show_recent_commits() {
    echo ""
    echo -e "${BLUE}📜 Recent Commits (last 10):${NC}"
    echo "─────────────────────────────────────────────────────────────"
    local i=1
    while IFS= read -r line; do
        echo "  [$i] $line"
        ((i++))
    done < <(git log --oneline -10 --format="%h  %s  (%cr)")
    echo "─────────────────────────────────────────────────────────────"
    echo ""
}

get_current_commit() {
    git rev-parse --short HEAD
}

get_commit_by_number() {
    local num=$1
    git log --oneline -10 --format="%h" | sed -n "${num}p"
}

validate_commit() {
    local commit=$1
    if git cat-file -t "$commit" > /dev/null 2>&1; then
        return 0
    else
        return 1
    fi
}

perform_health_check() {
    log_info "Performing health checks..."
    local failed=false
    
    # Check containers
    echo ""
    echo -e "${BLUE}📦 Container Status:${NC}"
    docker compose ps --format "table {{.Name}}\t{{.Status}}"
    echo ""
    
    # Check HTTP endpoints
    echo -e "${BLUE}🌐 HTTP Endpoints:${NC}"
    for endpoint in "${HEALTH_ENDPOINTS[@]}"; do
        local status
        status=$(curl -s -o /dev/null -w "%{http_code}" --max-time 10 "$endpoint" 2>/dev/null || echo "000")
        if [ "$status" = "200" ] || [ "$status" = "301" ] || [ "$status" = "302" ]; then
            echo -e "  ✅ $endpoint → HTTP $status"
        else
            echo -e "  ❌ $endpoint → HTTP $status"
            failed=true
        fi
    done
    echo ""
    
    if [ "$failed" = true ]; then
        return 1
    fi
    return 0
}

print_undo_command() {
    local previous_commit=$1
    echo ""
    echo -e "${YELLOW}🔙 To UNDO this rollback:${NC}"
    echo "   ./rollback.sh $previous_commit"
    echo ""
}

perform_rollback() {
    local commit=$1
    CURRENT_COMMIT=$(get_current_commit)
    
    echo ""
    log_info "Current commit: $CURRENT_COMMIT"
    log_info "Target commit:  $commit"
    echo ""
    
    # Show what will change
    echo -e "${BLUE}📋 Files that will change:${NC}"
    git diff --stat "$commit" HEAD 2>/dev/null | tail -20
    echo ""
    
    # DRY RUN MODE
    if [ "$DRY_RUN" = true ]; then
        log_warning "DRY RUN MODE - No changes will be made"
        echo ""
        echo -e "${BLUE}Commands that would run:${NC}"
        echo "  git checkout $commit -- ."
        if [ "$NO_CACHE" = true ]; then
            echo "  docker compose build --no-cache"
            echo "  docker compose up -d"
        else
            echo "  docker compose up -d --build"
        fi
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
    
    # Step 1: Checkout the target commit
    log_info "[1/3] Rolling back to commit $commit..."
    git checkout "$commit" -- .
    
    # Step 2: Rebuild/restart containers
    if [ "$NO_CACHE" = true ]; then
        log_info "[2/3] Rebuilding containers (no-cache)..."
        docker compose build --no-cache
        log_info "[3/3] Starting services..."
        docker compose up -d
    else
        log_info "[2/3] Rebuilding and starting services..."
        docker compose up -d --build
        log_info "[3/3] Waiting for services..."
    fi
    
    # Wait for services
    sleep 5
    
    # Health check
    echo ""
    if perform_health_check; then
        echo "==========================================="
        echo -e "${GREEN}✅ ROLLBACK SUCCESSFUL${NC}"
        echo "==========================================="
        echo ""
        echo "Rolled back from: $CURRENT_COMMIT"
        echo "Rolled back to:   $commit"
        print_undo_command "$CURRENT_COMMIT"
    else
        echo "==========================================="
        echo -e "${RED}❌ ROLLBACK COMPLETED BUT HEALTH CHECK FAILED${NC}"
        echo "==========================================="
        echo ""
        echo "Some services may not be responding correctly."
        echo ""
        echo -e "${YELLOW}Recommended actions:${NC}"
        echo "  1. Check logs: docker compose logs -f"
        echo "  2. Manual restart: docker compose restart"
        echo "  3. Undo rollback: ./rollback.sh $CURRENT_COMMIT"
        echo ""
        print_undo_command "$CURRENT_COMMIT"
        exit 1
    fi
}

show_help() {
    show_header
    echo "Usage: ./rollback.sh [OPTIONS] [COMMIT_HASH]"
    echo ""
    echo "Options:"
    echo "  --dry-run    Simulation mode, shows commands without executing"
    echo "  --no-cache   Force full Docker rebuild (slower but cleaner)"
    echo "  --force      Override dirty working tree check (dangerous)"
    echo "  --help       Show this help"
    echo ""
    echo "Examples:"
    echo "  ./rollback.sh              # Interactive mode"
    echo "  ./rollback.sh abc123       # Direct rollback to commit"
    echo "  ./rollback.sh --dry-run    # See what would happen"
    echo "  ./rollback.sh --no-cache abc123  # Full rebuild after rollback"
    echo ""
    echo "Health check endpoints:"
    for endpoint in "${HEALTH_ENDPOINTS[@]}"; do
        echo "  - $endpoint"
    done
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
        --no-cache)
            NO_CACHE=true
            shift
            ;;
        --force)
            FORCE=true
            shift
            ;;
        --help|-h)
            show_help
            exit 0
            ;;
        -*)
            log_error "Unknown option: $1"
            echo "Use --help for usage information."
            exit 1
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

if [ "$NO_CACHE" = true ]; then
    log_info "No-cache mode: Full Docker rebuild will be performed"
    echo ""
fi

# If no commit specified, interactive mode
if [ -z "$TARGET_COMMIT" ]; then
    show_recent_commits
    read -p "Enter commit number (1-10) or hash: " input
    
    if [ -z "$input" ]; then
        log_error "No commit specified!"
        exit 1
    fi
    
    # If input is a number 1-10, get the commit hash
    if [[ "$input" =~ ^[0-9]+$ ]] && [ "$input" -ge 1 ] && [ "$input" -le 10 ]; then
        TARGET_COMMIT=$(get_commit_by_number "$input")
        if [ -z "$TARGET_COMMIT" ]; then
            log_error "Could not find commit at position $input"
            exit 1
        fi
        log_info "Selected commit: $TARGET_COMMIT"
    else
        TARGET_COMMIT=$input
    fi
fi

# Validate commit
if ! validate_commit "$TARGET_COMMIT"; then
    log_error "Invalid commit: $TARGET_COMMIT"
    exit 1
fi

# Prevent rollback to current commit
if [ "$TARGET_COMMIT" = "$(get_current_commit)" ]; then
    log_warning "Target commit is the same as current commit!"
    log_info "Nothing to roll back."
    exit 0
fi

# Perform rollback
perform_rollback "$TARGET_COMMIT"
