#!/bin/bash

# ===========================================
# 🔥 Firewall Setup Script (UFW)
# Run on server: sudo ./firewall-setup.sh
# ===========================================

set -e

echo "🔥 Setting up firewall..."

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo "Please run as root (sudo ./firewall-setup.sh)"
    exit 1
fi

# Install UFW if not present
if ! command -v ufw &> /dev/null; then
    echo "Installing UFW..."
    apt update && apt install ufw -y
fi

# Reset UFW to defaults
ufw --force reset

# Set default policies
ufw default deny incoming
ufw default allow outgoing

# Allow SSH (important: do this first!)
ufw allow 22/tcp comment 'SSH'

# Allow HTTP and HTTPS
ufw allow 80/tcp comment 'HTTP'
ufw allow 443/tcp comment 'HTTPS'

# Enable UFW
echo "y" | ufw enable

# Show status
echo ""
echo "✅ Firewall configured!"
ufw status verbose
