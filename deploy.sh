#!/bin/bash
# Deploy theme changes to Cloudways via rsync over SSH.
# Run from repo root: ./deploy.sh [semplice5|semplice6|all]
# Requires SSH access as dylandibona to 164.90.140.41

set -e

SERVER="dylandibona@164.90.140.41"
REMOTE_THEMES="/home/master/applications/yetjkkygea/public_html/wp-content/themes"
LOCAL_THEMES="themes"
THEME="${1:-all}"

deploy_theme() {
  local theme="$1"
  echo "→ Deploying $theme..."
  rsync -avz --delete \
    --exclude=".git" \
    --exclude=".DS_Store" \
    --exclude="*.log" \
    "$LOCAL_THEMES/$theme/" \
    "$SERVER:$REMOTE_THEMES/$theme/"
  echo "✓ $theme deployed"
}

if [ "$THEME" = "all" ]; then
  deploy_theme "semplice5"
  deploy_theme "semplice6"
else
  deploy_theme "$THEME"
fi

echo ""
echo "Done. Changes are live on production."
