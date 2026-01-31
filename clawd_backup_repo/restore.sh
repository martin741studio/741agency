#!/bin/bash
set -e

# Prompt for keys if not in env
if [ -z "$GEMINI_API_KEY" ]; then
    read -p "Enter Gemini API Key (starts with AIza...): " GEMINI_API_KEY
fi
if [ -z "$OPENAI_API_KEY" ]; then
    read -p "Enter OpenAI API Key (starts with sk-proj...): " OPENAI_API_KEY
fi
if [ -z "$CLAWDBOT_GATEWAY_TOKEN" ]; then
    read -p "Enter Gateway Token (e.g. clawdbot-secret-token): " CLAWDBOT_GATEWAY_TOKEN
fi

echo "🦞 Restoring ClawdBot Configuration..."

mkdir -p ~/.clawdbot/agents/main/agent
mkdir -p ~/.clawdbot/credentials/whatsapp

# Restore global config
cp clawdbot.json ~/.clawdbot/clawdbot.json
# Inject token
sed -i '' "s/YOUR_GATEWAY_TOKEN/$CLAWDBOT_GATEWAY_TOKEN/g" ~/.clawdbot/clawdbot.json
# Inject Gemini Key into global config if it exists there (it shouldn't in our clean setup, but for safety)
sed -i '' "s/YOUR_GEMINI_KEY/$GEMINI_API_KEY/g" ~/.clawdbot/clawdbot.json

# Restore auth profile
cp auth-profiles.json ~/.clawdbot/agents/main/agent/auth-profiles.json
sed -i '' "s/YOUR_GEMINI_KEY/$GEMINI_API_KEY/g" ~/.clawdbot/agents/main/agent/auth-profiles.json
sed -i '' "s/YOUR_OPENAI_KEY/$OPENAI_API_KEY/g" ~/.clawdbot/agents/main/agent/auth-profiles.json

# Restore identity
echo "# ClawdBot" > ~/.clawdbot/agents/main/agent/identity.md

# Set Launchctl
echo "🚀 Setting Launchctl Environment..."
launchctl setenv GEMINI_API_KEY "$GEMINI_API_KEY"
launchctl setenv OPENAI_API_KEY "$OPENAI_API_KEY"
launchctl setenv CLAWDBOT_GATEWAY_TOKEN "$CLAWDBOT_GATEWAY_TOKEN"

echo "✅ Configuration Restored."
echo "👉 Run: clawdbot node restart"
