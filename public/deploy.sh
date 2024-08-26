#!/bin/bash

# Set HOME environment variable if not set
export HOME=/root  # or the appropriate home directory for the user running the script

LOGFILE=/var/www/staging.ippu.org/storage/logs/laravel.log
DEPLOY_DIR=/var/www/staging.ippu.org

cd "$DEPLOY_DIR" || exit

echo "Deployment started at $(date)" >> "$LOGFILE"

git reset --hard >> "$LOGFILE" 2>&1
git pull origin master >> "$LOGFILE" 2>&1
if [ $? -ne 0 ]; then
  echo "Git pull failed at $(date)" >> "$LOGFILE"
  exit 1
fi

git merge origin/master >> "$LOGFILE" 2>&1
if [ $? -ne 0 ]; then
  echo "Git merge failed at $(date)" >> "$LOGFILE"
  exit 1
fi

# Set COMPOSER_HOME environment variable if needed
export COMPOSER_HOME="$HOME/.composer"

# Run Composer install
composer install --optimize-autoloader --no-dev >> "$LOGFILE" 2>&1
if [ $? -ne 0 ]; then
  echo "Composer install failed at $(date)" >> "$LOGFILE"
  exit 1
fi

php artisan migrate --force >> "$LOGFILE" 2>&1
php artisan config:cache >> "$LOGFILE" 2>&1

echo "Deployment successful at $(date)" >> "$LOGFILE"
