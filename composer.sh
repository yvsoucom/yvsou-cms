#!/bin/bash

# SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
  
# SPDX-FileContributor: Lican Huang
# SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

echo "🚀 Starting yvsou-cms composer..."

# 1. Check PHP
if ! command -v php &> /dev/null; then
  echo "❌ PHP is not installed. Aborting."
  exit 1
fi

# 2. Download Composer if not present
if ! command -v composer &> /dev/null; then
  echo "📦 Composer not found. Downloading..."
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php composer-setup.php --install-dir=/usr/local/bin --filename=composer
  rm composer-setup.php
fi

# 3. Install dependencies
echo "📚 Running composer install..."
composer install --no-interaction --prefer-dist --optimize-autoloader

 
echo "✅ Laravel installation completed!"
echo "👉 Now you can run: php artisan serve"
