ver="0.9.0"
rm -rf vendor
mv  .env  .env.bak
cp env_install  .env     
mv config/yvsou_config.php yvsou_config_bak.php
cp yvsou_install_config.php config/yvsou_config.php
 
composer install --no-dev --optimize-autoloader
npm run build    # or skip if no frontend
php artisan config:cache
php artisan route:cache
php artisan view:cache
  
npm install
npm run build  
 
 
# Optional: remove compiled files manually
# rm -f bootstrap/cache/*.php

   
cd ..
zip -r yvsou-cms/yvsou-cms-installer-${ver}.zip   \
    yvsou-cms/app yvsou-cms/bootstrap yvsou-cms/config yvsou-cms/database yvsou-cms/public yvsou-cms/resources yvsou-cms/routes  yvsou-cms/vendor  \
    yvsou-cms/composer.json yvsou-cms/composer.lock\
    yvsou-cms/env.example yvsou-cms/yvsou_example_config.php \
    yvsou-cms/.env   \
    yvsou-cms/install.sql yvsou-cms/server.php \
    --exclude=yvsou-cms/*.log --exclude=yvsou-cms/node_modules/* \
    --exclude=yvsou-cms/.git/*   \
    --exclude=yvsou-cms/bootstrap/cache/*.php   

cd ./yvsou-cms

# finished move back
#mv  .env.bak  .env  
#mv  yvsou_config_bak.php config/yvsou_config.php    
 

   
 