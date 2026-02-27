#!/bin/bash

# ===========================================
# 🔍 File Integrity Monitor
# ===========================================
# Detects unauthorized file changes in WordPress
# Run via cron: */5 * * * * /var/www/vahidrajabloo-platform/scripts/file-monitor.sh
# ===========================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
BASELINE_FILE="${PROJECT_DIR}/.file-integrity-baseline.md5"
ALERT_LOG="/var/log/wp-file-monitor.log"
CONTAINER="wordpress"

# Monitored paths (PHP files only - most dangerous)
MONITORED_PATHS=(
    "/var/www/html/*.php"
    "/var/www/html/wp-admin/*.php"
    "/var/www/html/wp-includes/*.php"
    "/var/www/html/wp-content/themes/*/*.php"
    "/var/www/html/wp-content/mu-plugins/*.php"
)

log_alert() {
    local msg="[$(date '+%Y-%m-%d %H:%M:%S')] ALERT: $1"
    echo "$msg" | tee -a "$ALERT_LOG"
}

log_info() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] INFO: $1"
}

# Generate current hash
generate_current_hash() {
    local tmp_file=$(mktemp)
    for path in "${MONITORED_PATHS[@]}"; do
        docker exec "$CONTAINER" find $path -type f 2>/dev/null | \
        while read -r file; do
            docker exec "$CONTAINER" md5sum "$file" 2>/dev/null
        done
    done | sort > "$tmp_file"
    echo "$tmp_file"
}

# Create baseline
create_baseline() {
    log_info "Creating baseline..."
    local current=$(generate_current_hash)
    mv "$current" "$BASELINE_FILE"
    log_info "Baseline created with $(wc -l < "$BASELINE_FILE") files"
}

# Check for changes
check_integrity() {
    if [ ! -f "$BASELINE_FILE" ]; then
        log_info "No baseline found. Creating..."
        create_baseline
        exit 0
    fi
    
    local current=$(generate_current_hash)
    local diff_result=$(diff "$BASELINE_FILE" "$current" 2>/dev/null || true)
    
    if [ -n "$diff_result" ]; then
        log_alert "FILE CHANGES DETECTED!"
        echo "$diff_result" | while read -r line; do
            log_alert "$line"
        done
        
        # Show specific changes
        echo ""
        log_alert "Changed files:"
        diff "$BASELINE_FILE" "$current" | grep "^[<>]" | while read -r line; do
            log_alert "$line"
        done
        
        rm -f "$current"
        exit 1
    else
        log_info "Integrity check passed. No changes detected."
        rm -f "$current"
        exit 0
    fi
}

# Command handling
case "${1:-check}" in
    baseline|create)
        create_baseline
        ;;
    check)
        check_integrity
        ;;
    *)
        echo "Usage: $0 {baseline|check}"
        exit 1
        ;;
esac
