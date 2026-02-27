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
#   ssh root@116.203.78.31 "cd /var/www/vahidrajabloo-platform && ./deploy.sh"
#
# ===========================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo ""
echo "=========================================="
echo "🚀 VahidRajabloo Platform - Deployment"
echo "=========================================="
echo ""

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
if [ -n "$TRACKED_CHANGES" ]; then
    echo -e "${RED}⚠️  WARNING: Tracked local changes detected!${NC}"
    echo "Tracked changes:"
    git status --short --untracked-files=no
    echo ""
    echo -e "${RED}This violates the Golden Rule!${NC}"
    echo "Options:"
    echo "  1. Discard tracked changes: git reset --hard HEAD"
    echo "  2. Or commit locally first and push"

    if [ -t 0 ]; then
        read -p "Discard tracked changes and continue? (y/N): " confirm
        if [ "$confirm" != "y" ]; then
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
git pull origin main
echo -e "${GREEN}✓ Code updated${NC}"

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

# Step 4: Build and restart containers
echo -e "${YELLOW}[4/5] Building containers...${NC}"
docker compose build --no-cache
echo -e "${GREEN}✓ Build complete${NC}"

echo -e "${YELLOW}[5/5] Restarting services...${NC}"
docker compose up -d
echo -e "${GREEN}✓ Services started${NC}"

# Step 5: Health check
echo ""
echo -e "${YELLOW}Waiting for services to start...${NC}"
sleep 5

# Check if nginx is running
if docker ps | grep -q nginx; then
    echo -e "${GREEN}✓ Nginx: Running${NC}"
else
    echo -e "${RED}✗ Nginx: Not running${NC}"
fi

# Check if wordpress is running
if docker ps | grep -q wordpress; then
    echo -e "${GREEN}✓ WordPress: Running${NC}"
else
    echo -e "${RED}✗ WordPress: Not running${NC}"
fi

# Check if mysql is running
if docker ps | grep -q mysql; then
    echo -e "${GREEN}✓ MySQL: Running${NC}"
else
    echo -e "${RED}✗ MySQL: Not running${NC}"
fi

echo ""
echo "=========================================="
echo -e "${GREEN}✅ Deployment Complete!${NC}"
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
