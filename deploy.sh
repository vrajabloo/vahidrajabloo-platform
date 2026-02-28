#!/bin/bash

# ===========================================
# 🚀 VahidRajabloo Platform - Production Deployment
# ===========================================
#
# GOLDEN RULE:
# ❌ No direct edits on server
# ✅ Only GitHub → deploy.sh
#
# Usage:
#   ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"
#   ssh deploy@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh --full-rebuild"
# ===========================================

set -euo pipefail

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

FULL_REBUILD="${FULL_REBUILD:-0}"
CHANGED_FILES=""

print_usage() {
    cat <<EOF
Usage: ./deploy.sh [--full-rebuild] [--help]

Options:
  --full-rebuild   Force clean Docker image rebuild (no cache).
                   Use weekly for security patch freshness.
  --help           Show this help message.

Environment:
  FULL_REBUILD=1   Same as --full-rebuild
EOF
}

contains_changed_pattern() {
    local pattern="$1"
    if [ -z "${CHANGED_FILES}" ]; then
        return 1
    fi
    echo "${CHANGED_FILES}" | grep -Eq "${pattern}"
}

check_container_running() {
    local name="$1"
    if docker ps --format '{{.Names}}' | grep -qx "${name}"; then
        echo -e "${GREEN}✓ ${name}: Running${NC}"
        return 0
    fi
    echo -e "${RED}✗ ${name}: Not running${NC}"
    return 1
}

check_http_endpoint() {
    local label="$1"
    local host="$2"
    local path="$3"
    if curl -fsS --max-time 15 -o /dev/null -H "Host: ${host}" "http://127.0.0.1${path}"; then
        echo -e "${GREEN}✓ ${label}: HTTP 200${NC}"
        return 0
    fi
    echo -e "${RED}✗ ${label}: HTTP check failed${NC}"
    return 1
}

for arg in "$@"; do
    case "${arg}" in
        --full-rebuild)
            FULL_REBUILD=1
            ;;
        --help|-h)
            print_usage
            exit 0
            ;;
        *)
            echo -e "${RED}Unknown argument: ${arg}${NC}"
            print_usage
            exit 1
            ;;
    esac
done

echo ""
echo "=========================================="
echo "🚀 VahidRajabloo Platform - Deployment"
echo "=========================================="
echo ""

if [ "${FULL_REBUILD}" = "1" ]; then
    echo -e "${YELLOW}Mode: FULL REBUILD (no cache)${NC}"
else
    echo -e "${YELLOW}Mode: FAST DEPLOY (cached)${NC}"
fi

# Check if git repository
if [ ! -d ".git" ]; then
    echo -e "${RED}Error: Not a git repository!${NC}"
    echo "First time setup? Run:"
    echo "  git clone <your-repo-url> ."
    exit 1
fi

# Step 1: Check for tracked local changes (runtime untracked files are allowed)
echo -e "${YELLOW}[1/5] Checking for tracked local changes...${NC}"
TRACKED_CHANGES="$(git status --porcelain --untracked-files=no)"
if [ -n "${TRACKED_CHANGES}" ]; then
    echo -e "${RED}⚠️  WARNING: Tracked local changes detected!${NC}"
    echo "Tracked changes:"
    git status --short --untracked-files=no
    echo ""
    echo -e "${RED}This violates the Golden Rule!${NC}"
    echo "Options:"
    echo "  1. Discard tracked changes: git reset --hard HEAD"
    echo "  2. Or commit locally first and push"

    if [ -t 0 ]; then
        read -r -p "Discard tracked changes and continue? (y/N): " confirm
        if [ "${confirm}" != "y" ]; then
            echo "Deployment cancelled."
            exit 1
        fi
        git reset --hard HEAD
    else
        echo -e "${RED}Non-interactive session detected. Aborting deploy to protect tracked changes.${NC}"
        echo "Fix by running one of these commands first:"
        echo "  git reset --hard HEAD"
        echo "  git commit -am \"...\" && git push"
        exit 1
    fi
fi
echo -e "${GREEN}✓ No tracked local changes${NC}"

# Informative only: untracked runtime files should not block deploy
UNTRACKED_COUNT="$(git ls-files --others --exclude-standard | wc -l | tr -d ' ')"
if [ "${UNTRACKED_COUNT}" -gt 0 ]; then
    echo -e "${YELLOW}ℹ Found ${UNTRACKED_COUNT} untracked file(s) (runtime files/plugins); deploy will continue.${NC}"
fi

# Step 2: Pull from GitHub
echo -e "${YELLOW}[2/5] Pulling from GitHub...${NC}"
PRE_PULL_COMMIT="$(git rev-parse HEAD)"
git pull origin main
POST_PULL_COMMIT="$(git rev-parse HEAD)"

if [ "${PRE_PULL_COMMIT}" = "${POST_PULL_COMMIT}" ]; then
    echo -e "${GREEN}✓ Code already up-to-date${NC}"
else
    CHANGED_FILES="$(git diff --name-only "${PRE_PULL_COMMIT}" "${POST_PULL_COMMIT}")"
    echo -e "${GREEN}✓ Code updated${NC}"
    echo -e "${YELLOW}Changed files in this deploy:${NC}"
    echo "${CHANGED_FILES}" | sed 's/^/  - /'
fi

# Step 3: Check .env file
echo -e "${YELLOW}[3/5] Checking environment...${NC}"
if [ ! -f ".env" ]; then
    if [ -f ".env.production" ]; then
        cp .env.production .env
        echo "Created .env from .env.production"
    else
        echo -e "${RED}Error: No .env file found!${NC}"
        exit 1
    fi
fi
echo -e "${GREEN}✓ Environment ready${NC}"

# Step 4: Build strategy
echo -e "${YELLOW}[4/5] Building containers...${NC}"
NEEDS_LARAVEL_REBUILD=0
if [ "${FULL_REBUILD}" = "1" ]; then
    NEEDS_LARAVEL_REBUILD=1
elif contains_changed_pattern '^docker/laravel/' || contains_changed_pattern '^docker-compose\.yml$'; then
    NEEDS_LARAVEL_REBUILD=1
fi

if [ "${NEEDS_LARAVEL_REBUILD}" = "1" ]; then
    if [ "${FULL_REBUILD}" = "1" ]; then
        docker compose build --no-cache laravel
    else
        docker compose build laravel
    fi
    echo -e "${GREEN}✓ Laravel image build complete${NC}"
else
    echo -e "${YELLOW}ℹ Skipping image rebuild (no container-level changes detected)${NC}"
fi

echo -e "${YELLOW}[5/5] Restarting services...${NC}"
docker compose up -d

# Refresh nginx DNS mapping only when container topology might change.
if [ "${NEEDS_LARAVEL_REBUILD}" = "1" ] || [ "${FULL_REBUILD}" = "1" ]; then
    docker compose up -d --no-deps --force-recreate nginx
    echo -e "${GREEN}✓ Nginx recreated (upstream DNS refresh)${NC}"
fi

# Keep runtime dependencies in sync when composer files changed.
if contains_changed_pattern '^laravel/composer\.json$' || contains_changed_pattern '^laravel/composer\.lock$'; then
    echo -e "${YELLOW}Syncing Laravel vendor dependencies...${NC}"
    docker compose exec -T laravel sh -lc 'cd /var/www/laravel && composer install --no-dev --optimize-autoloader --no-interaction'
    echo -e "${GREEN}✓ Laravel dependencies updated${NC}"
fi

# Clear Laravel app caches when Laravel code changed.
if contains_changed_pattern '^laravel/'; then
    docker compose exec -T laravel php artisan optimize:clear >/dev/null
    echo -e "${GREEN}✓ Laravel runtime cache cleared${NC}"
fi

# Reset WordPress opcode cache for PHP/theme/plugin updates.
if contains_changed_pattern '^wordpress/'; then
    docker compose exec -T wordpress sh -lc 'php -r "if (function_exists(\"opcache_reset\")) { opcache_reset(); }"' >/dev/null || true
    echo -e "${GREEN}✓ WordPress OPCache reset${NC}"
fi

# Purge Nginx FastCGI cache to avoid stale HTML after deploy.
docker exec nginx sh -lc 'rm -rf /var/cache/nginx/fastcgi/*' >/dev/null 2>&1 || true
echo -e "${GREEN}✓ Nginx FastCGI cache purged${NC}"

echo -e "${GREEN}✓ Services started${NC}"

# Health checks
echo ""
echo -e "${YELLOW}Waiting for services to start...${NC}"
sleep 5

HEALTH_FAILED=0
check_container_running "nginx" || HEALTH_FAILED=1
check_container_running "wordpress" || HEALTH_FAILED=1
check_container_running "mysql" || HEALTH_FAILED=1
check_container_running "laravel" || HEALTH_FAILED=1

check_http_endpoint "Website" "vahidrajabloo.com" "/" || HEALTH_FAILED=1
check_http_endpoint "App Login" "app.vahidrajabloo.com" "/dashboard/login" || HEALTH_FAILED=1

echo ""
echo "=========================================="
if [ "${HEALTH_FAILED}" -eq 0 ]; then
    echo -e "${GREEN}✅ Deployment Complete!${NC}"
else
    echo -e "${RED}❌ Deployment completed with health-check errors${NC}"
fi
echo "=========================================="
echo ""

# Log deployment
if [ -f "./scripts/deploy-log.sh" ]; then
    chmod +x ./scripts/deploy-log.sh 2>/dev/null || true
    ./scripts/deploy-log.sh "Deploy via deploy.sh" 2>/dev/null || true
fi

# Update file integrity baseline
if [ -f "./scripts/file-monitor.sh" ]; then
    chmod +x ./scripts/file-monitor.sh 2>/dev/null || true
    ./scripts/file-monitor.sh baseline 2>/dev/null || true
fi

echo "🌐 Website: https://vahidrajabloo.com"
echo "🌐 App:     https://app.vahidrajabloo.com"
echo ""
echo "Deployed commit:"
git log -1 --oneline
echo ""

if [ "${HEALTH_FAILED}" -ne 0 ]; then
    exit 1
fi
