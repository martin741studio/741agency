#!/bin/bash

# Configuration
KEY_PATH="$HOME/.ssh/id_rsa"
LOCAL_FILE="reports/reload_sanctuary/reload_sanctuary_report_2026_02.html"
DEFAULT_REMOTE_PATH="public_html"

# Usage check
if [ "$#" -lt 0 ]; then
    echo "Usage: $0 <HOST_IP> <USERNAME> [REMOTE_PATH]"
    echo "Example: $0 1.2.3.4 reload /home/reload/public_html"
    exit 1
fi

HOST="128.127.111.40"
USER="studionew741"
REMOTE_DIR="public_html"

echo "🚀 Deploying to $USER@$HOST:$REMOTE_DIR..."

# 1. Create directory if it doesn't exist (robustness)
ssh -p 6121 -i "$KEY_PATH" -o StrictHostKeyChecking=no "$USER@$HOST" "mkdir -p $REMOTE_DIR/clients/reload-sanctuary"

# 2. Upload file
scp -P 6121 -i "$KEY_PATH" -o StrictHostKeyChecking=no "$LOCAL_FILE" "$USER@$HOST:$REMOTE_DIR/clients/reload-sanctuary/index.html"

if [ $? -eq 0 ]; then
    echo "✅ Success! Report deployed."
    echo "🔗 URL should be: http://$HOST/clients/reload-sanctuary/ (or similar, depending on server config)"
else
    echo "❌ Deployment failed. Check SSH keys and permissions."
    exit 1
fi
