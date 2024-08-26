#!/bin/bash

# Set HOME environment variable if not set
export HOME=/root  # or the appropriate home directory for the user running the script

LOGFILE=/var/www/staging.ippu.org/storage/logs/laravel.log
DEPLOY_DIR=/var/www/staging.ippu.org

cd "$DEPLOY_DIR" || exit

echo "Deployment started at $(date)" >> "$LOGFILE"

# Reset changes to ensure a clean working directory
git reset --hard >> "$LOGFILE" 2>&1
if [ $? -ne 0 ]; then
  echo "Git reset failed at $(date)" >> "$LOGFILE"
  exit 1
fi

# Pull the latest changes from the repository
git pull origin master >> "$LOGFILE" 2>&1
if [ $? -ne 0 ]; then
  echo "Git pull failed at $(date)" >> "$LOGFILE"
  exit 1
fi

# Merge the changes from the master branch
git merge origin/master >> "$LOGFILE" 2>&1
if [ $? -ne 0 ]; then
  echo "Git merge failed at $(date)" >> "$LOGFILE"
  exit 1
fi

# Set COMPOSER_HOME environment variable if needed
export COMPOSER_HOME="$HOME/.composer"

# Clear Composer cache to avoid outdated or corrupt packages
composer clear-cache >> "$LOGFILE" 2>&1
if [ $? -ne 0 ]; then
  echo "Composer clear-cache failed at $(date)" >> "$LOGFILE"
  exit 1
fi

# Remove the vendor directory to ensure a fresh install of dependencies
rm -rf vendor/ >> "$LOGFILE" 2>&1

# Install Composer dependencies and optimize autoloader
composer install --optimize-autoloader --no-dev >> "$LOGFILE" 2>&1
if [ $? -ne 0 ]; then
  echo "Composer install failed at $(date)" >> "$LOGFILE"
  exit 1
fi

# Set permissions for the vendor directory
chown -R www-data:www-data vendor/ >> "$LOGFILE" 2>&1
chmod -R 755 vendor/ >> "$LOGFILE" 2>&1
if [ $? -ne 0 ]; then
  echo "Setting permissions failed at $(date)" >> "$LOGFILE"
  exit 1
fi

# Run database migrations
php artisan migrate --force >> "$LOGFILE" 2>&1
if [ $? -ne 0 ]; then
  echo "Database migration failed at $(date)" >> "$LOGFILE"
  exit 1
fi

# Cache the config and routes
php artisan config:cache >> "$LOGFILE" 2>&1
if [ $? -ne 0 ]; then
  echo "Config caching failed at $(date)" >> "$LOGFILE"
  exit 1
fi

# Uncomment the lines below if you're using npm for building assets
# npm install >> "$LOGFILE" 2>&1
# if [ $? -ne 0 ]; then
#   echo "npm install failed at $(date)" >> "$LOGFILE"
#   exit 1
# fi

# npm run build >> "$LOGFILE" 2>&1
# if [ $? -ne 0 ]; then
#   echo "npm run build failed at $(date)" >> "$LOGFILE"
#   exit 1
# fi

# Log a deployment success message
echo "Deployment successful at $(date)" >> "$LOGFILE"
