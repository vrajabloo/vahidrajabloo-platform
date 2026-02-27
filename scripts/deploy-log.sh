#!/bin/bash

# ===========================================
# 📋 Deploy Logger
# ===========================================
# Logs all deployments with commit info, user, and timestamp
# Usage: ./scripts/deploy-log.sh "Optional notes"
# ===========================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
LOG_FILE="${PROJECT_DIR}/deploy-history.log"

# Get info
TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S %Z')
COMMIT=$(git rev-parse --short HEAD 2>/dev/null || echo "unknown")
BRANCH=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "unknown")
COMMIT_MSG=$(git log -1 --format="%s" 2>/dev/null || echo "unknown")
USER=$(whoami)
HOSTNAME=$(hostname)
NOTES="${1:-}"

# Create log entry
cat >> "$LOG_FILE" << EOF
================================================================================
DEPLOY: $TIMESTAMP
================================================================================
Commit:   $COMMIT ($BRANCH)
Message:  $COMMIT_MSG
User:     $USER@$HOSTNAME
Notes:    $NOTES
--------------------------------------------------------------------------------

EOF

echo "Deploy logged: $COMMIT at $TIMESTAMP"
