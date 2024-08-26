#!/bin/bash

LOGFILE=/var/www/staging.ippu.org/storage/logs/laravel.log
DEPLOY_DIR=/var/www/staging.ippu.org

# Change to the deployment directory
cd "$DEPLOY_DIR" || exit

# Log the deployment start time
echo "Deployment started at $(date)" >> "$LOGFILE"

# Ensure there are no uncommitted changes
git reset --hard >> "$LOGFILE" 2>&1

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

# Install dependencies and compile assets
composer install --optimize-autoloader --no-dev >> "$LOGFILE" 2>&1
if [ $? -ne 0 ]; then
  echo "Composer install failed at $(date)" >> "$LOGFILE"
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
