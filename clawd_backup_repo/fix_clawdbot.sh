#!/bin/bash
set -e

echo "🦞 Stops existing ClawdBot..."
pkill -9 -f clawdbot || true

echo "🧹 Cleaning configuration..."
rm -rf ~/.clawdbot
mkdir -p ~/.clawdbot/agents/main/agent
mkdir -p ~/.clawdbot/credentials/whatsapp

echo "📝 Writing Global Config..."
cat > ~/.clawdbot/clawdbot.json <<EOF
{
  "auth": {
    "profiles": {
      "openai": {
        "provider": "openai",
        "mode": "api_key"
      }
    }
  },
  "agents": {
    "defaults": {
      "model": {
        "primary": "openai/gpt-4o"
      },
      "workspace": "/Users/martindrendel/clawd",
      "maxConcurrent": 4
    }
  },
  "gateway": {
    "port": 18789,
    "mode": "local",
    "auth": {
      "mode": "token",
      "token": "clawdbot-secret-token"
    }
  },
  "channels": {
    "whatsapp": {}
  },
  "plugins": {
    "entries": {
        "whatsapp": {
            "enabled": true
        }
    }
  }
}
EOF

echo "🔑 Writing Agent Auth..."
mkdir -p ~/.clawdbot/agents/main/agent
cat > ~/.clawdbot/agents/main/agent/auth-profiles.json <<EOF
{
  "profiles": {
    "openai": { "provider": "openai", "mode": "api_key", "apiKey": "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" }
  },
  "openai": { "provider": "openai", "mode": "api_key", "apiKey": "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" }
}
EOF

echo "🆔 Writing Agent Identity..."
echo "# ClawdBot" > ~/.clawdbot/agents/main/agent/identity.md

echo "⚙️  Starting Service..."
# Ensure env vars are set for this session just in case
export OPENAI_API_KEY="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"
export CLAWDBOT_GATEWAY_TOKEN="clawdbot-secret-token"

# Propagate to launchd to be safe
launchctl setenv OPENAI_API_KEY "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"
launchctl setenv CLAWDBOT_GATEWAY_TOKEN "clawdbot-secret-token"

# Restart
clawdbot node restart

echo "✅ Done! Waiting 5s for startup..."
sleep 5
clawdbot status
