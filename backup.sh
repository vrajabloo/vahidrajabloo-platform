#!/bin/bash

# ===========================================
# 💾 Database Backup Script
# ===========================================
# Usage: ./backup.sh
# Add to crontab for daily backups:
#   0 2 * * * /path/to/backup.sh

set -e

# Configuration
BACKUP_DIR="/var/backups/vahidrajabloo"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
KEEP_DAYS=7

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}💾 Starting backup...${NC}"

# Script directory
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$SCRIPT_DIR"

# Create backup directory
mkdir -p $BACKUP_DIR

# Get MySQL password from environment
if [ -f "$SCRIPT_DIR/.env" ]; then
    source "$SCRIPT_DIR/.env"
elif [ -f "$SCRIPT_DIR/.env.production" ]; then
    source "$SCRIPT_DIR/.env.production"
else
    # Use hardcoded password as fallback
    MYSQL_ROOT_PASSWORD="Lc80WMTBioNmyeFqdbeyr6NXaJmekuHZ"
fi

# Backup databases
echo "Backing up MySQL databases..."

# WordPress database
docker exec mysql mysqldump -u root -p"${MYSQL_ROOT_PASSWORD}" wordpress > "${BACKUP_DIR}/wordpress_${TIMESTAMP}.sql"

# Laravel database
docker exec mysql mysqldump -u root -p"${MYSQL_ROOT_PASSWORD}" laravel > "${BACKUP_DIR}/laravel_${TIMESTAMP}.sql"

# Compress backups
echo "Compressing backups..."
gzip "${BACKUP_DIR}/wordpress_${TIMESTAMP}.sql"
gzip "${BACKUP_DIR}/laravel_${TIMESTAMP}.sql"

# Cleanup old backups
echo "Cleaning up old backups (older than ${KEEP_DAYS} days)..."
find $BACKUP_DIR -name "*.sql.gz" -mtime +$KEEP_DAYS -delete

# List current backups
echo ""
echo -e "${GREEN}✅ Backup complete!${NC}"
echo "Backup location: $BACKUP_DIR"
echo ""
echo "Current backups:"
ls -lh $BACKUP_DIR/*.sql.gz 2>/dev/null | tail -10

# Calculate total size
TOTAL_SIZE=$(du -sh $BACKUP_DIR 2>/dev/null | cut -f1)
echo ""
echo "Total backup size: ${TOTAL_SIZE}"
