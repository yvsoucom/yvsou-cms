#!/bin/bash

 
# SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
# SPDX-FileCopyrightText: 2025  
# SPDX-FileContributor: Lican Huang
# SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

ver="1.0.0-beta.4"

echo "Preparing installer version ${ver}..."

# 1️⃣ Clean up
rm -rf vendor

# 2️⃣ Install production deps
composer install --no-dev --optimize-autoloader

# 3️⃣ Build frontend if needed
npm ci && npm run build

# 4️⃣ Cache configs
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5️⃣ Create temp build dir
rm -rf build
mkdir -p build/yvsou-cms

# 6️⃣ Copy project files
rsync -av --exclude="vendor" \
  --exclude="node_modules" \
  --exclude=".git" \
  --exclude="bootstrap/cache/*.php" \
  --exclude="*.log" \
  --exclude="storage" \
  --exclude="tests" \
  --exclude="*.sh" \
  ./ build/yvsou-cms/

# 7️⃣ Overwrite .env & config in build dir
cp env_install build/yvsou-cms/.env
cp yvsou_install_config.php build/yvsou-cms/config/yvsou_config.php
 
# 8️⃣ Copy only necessary files
# If you want more control, adjust rsync or zip list instead

# 9️⃣ Zip the installer
cd build
mkdir -p yvsou-cms/storage
mkdir -p yvsou-cms/storage/app
 
mkdir -p yvsou-cms/storage/app/private
mkdir -p yvsou-cms/storage/app/public
mkdir -p yvsou-cms/storage/app/protected-files

mkdir -p yvsou-cms/storage/framework
mkdir -p yvsou-cms/storage/framework/cache
mkdir -p yvsou-cms/storage/framework/sessions
mkdir -p yvsou-cms/storage/framework/testing
mkdir -p yvsou-cms/storage/framework/views
mkdir -p yvsou-cms/storage/logs

cp ../storage/tmp-install.sqlite  yvsou-cms/storage/tmp-install.sqlite  
 
zip -r "../yvsou-cms-installer-${ver}.zip" yvsou-cms

cd ..
rm -rf build/yvsou-cms
rm -rf build

echo "✅ Installer created: yvsou-cms-installer-${ver}.zip"
 