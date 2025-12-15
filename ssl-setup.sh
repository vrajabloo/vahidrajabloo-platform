#!/bin/bash

# ===========================================
# 🔒 SSL Setup Script (Let's Encrypt)
# ===========================================

set -e

DOMAIN="vahidrajabloo.com"
EMAIL="admin@vahidrajabloo.com"  # Change this to your email

echo "🔒 Setting up SSL for $DOMAIN..."

# Stop nginx temporarily
docker compose stop nginx

# Get certificate
docker compose run --rm certbot certonly \
    --webroot \
    --webroot-path=/var/www/certbot \
    --email $EMAIL \
    --agree-tos \
    --no-eff-email \
    -d $DOMAIN \
    -d www.$DOMAIN \
    -d app.$DOMAIN

# Update nginx config for SSL
cat > docker/nginx/default.conf << 'EOF'
# Global settings
client_max_body_size 256M;

# HTTP -> HTTPS redirect
server {
    listen 80;
    server_name vahidrajabloo.com www.vahidrajabloo.com app.vahidrajabloo.com;
    
    location /.well-known/acme-challenge/ {
        root /var/www/certbot;
    }
    
    location / {
        return 301 https://$host$request_uri;
    }
}

# WordPress - HTTPS
server {
    listen 443 ssl http2;
    server_name vahidrajabloo.com www.vahidrajabloo.com;

    ssl_certificate /etc/letsencrypt/live/vahidrajabloo.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/vahidrajabloo.com/privkey.pem;
    
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256;
    ssl_prefer_server_ciphers off;

    root /var/www/html;
    index index.php index.html;

    client_max_body_size 256M;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Strict-Transport-Security "max-age=31536000" always;

    location / {
        try_files $uri /index.php?$args;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass wordpress:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_read_timeout 300;
    }

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires max;
        log_not_found off;
        access_log off;
        try_files $uri =404;
    }

    location ~ /\. {
        deny all;
    }

    location ~* /(?:uploads|files)/.*\.php$ {
        deny all;
    }
}

# Laravel App - HTTPS
server {
    listen 443 ssl http2;
    server_name app.vahidrajabloo.com;

    ssl_certificate /etc/letsencrypt/live/vahidrajabloo.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/vahidrajabloo.com/privkey.pem;
    
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256;
    ssl_prefer_server_ciphers off;

    root /var/www/laravel/public;
    index index.php index.html;

    client_max_body_size 256M;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Strict-Transport-Security "max-age=31536000" always;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass laravel:9000;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_read_timeout 300;
    }

    location ~ /\. {
        deny all;
    }
}
EOF

# Restart nginx
docker compose up -d nginx

echo "✅ SSL setup complete!"
echo "🌐 Visit: https://vahidrajabloo.com"
