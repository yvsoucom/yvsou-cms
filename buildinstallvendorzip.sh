#!/bin/bash
 
# SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
# SPDX-FileCopyrightText: 2025 Institute of Future Science and Technology G.K., Tokyo
# SPDX-FileContributor: Lican Huang
# SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

ver="2.0.0-beta.7"

echo "Cleaning up..."
rm -rf vendor

echo "Installing composer dependencies..."
composer install --no-dev --optimize-autoloader

echo "Building frontend..."
npm ci && npm run build

echo "Caching configs..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Preparing build directory..."
rm -rf build
mkdir -p build/yvsou-cms

echo "Copying project files..."
rsync -av \
  --exclude="vendor" \
  --exclude="node_modules" \
  --exclude=".git" \
  --exclude="*.log" \
  --exclude="bootstrap/cache/*.php" \
  --exclude="storage" \
  --exclude="tests" \
  --exclude="*.sh" \
  ./ build/yvsou-cms/

echo "Copying vendor folder..."
cp -r vendor build/yvsou-cms/vendor

echo "Using installer versions of config and env..."
 

echo "Zipping installer..."
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
 
zip -r "../yvsou-cms-vendor-installer-${ver}.zip" yvsou-cms
cd ..

echo "Cleaning up temp build dir..."
rm -rf build/yvsou-cms
rm -rf build

echo "✅ Done! Your installer is: yvsou-cms-vendor-installer-${ver}.zip"
 