#!/bin/bash
# ============================================
# Deploy Script — Toko Sparepart Otomotif
# ============================================
# Usage: bash scripts/deploy.sh
# Run this on the production server after git pull

set -e

echo "🚀 Starting deployment..."

# 1. Install/update PHP dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 2. Install/update Node dependencies and build assets
echo "🎨 Building frontend assets..."
npm ci --production=false
npm run build

# 3. Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 4. Optimize Laravel
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 5. Storage link
echo "🔗 Linking storage..."
php artisan storage:link 2>/dev/null || true

# 6. Clear old caches
echo "🧹 Clearing old caches..."
php artisan optimize:clear 2>/dev/null || true

echo ""
echo "✅ Deployment complete!"
echo "📅 $(date '+%Y-%m-%d %H:%M:%S')"
