#!/bin/bash

set -euo pipefail

PROJECT_DIR="/var/www/vahidrajabloo-platform"
LOG_FILE="/var/log/vahidrajabloo/weekly-full-rebuild.log"

mkdir -p "$(dirname "${LOG_FILE}")"

{
    echo "[$(date -u '+%Y-%m-%d %H:%M:%S UTC')] Starting weekly full rebuild deploy"
    cd "${PROJECT_DIR}"
    FULL_REBUILD=1 ./deploy.sh --full-rebuild
    echo "[$(date -u '+%Y-%m-%d %H:%M:%S UTC')] Weekly full rebuild deploy completed"
} >>"${LOG_FILE}" 2>&1
