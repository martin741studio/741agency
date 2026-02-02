---
description: How to set up Clawdbot with Telegram (Clean Path)
---

# Setup Clawdbot with Telegram

Follow this proven path to avoid configuration loops and permission errors.

## 1. Environment Setup (`.env`)

Create or update `~/clawdbot-node/.env` with **all** required keys.
**Important:** Include `TELEGRAM_CHAT_ID` to prevent "Unknown target" errors.

```bash
GOOGLE_API_KEY=AIzaSy...               # Use a billing-enabled key or monitor Free Tier quota
TELEGRAM_BOT_TOKEN=123456:ABC-DEF...   # From @BotFather
CLAWDBOT_SECRET=some-secret-token      # For gateway auth
TELEGRAM_CHAT_ID=349191262             # Your numeric User ID (Get from @userinfobot)
TELEGRAM_USER_ID=349191262             # Same as above
```

## 2. Configuration (`config/clawdbot.json`)

Ensure `channels.telegram` is enabled. Use `env:` references to keep secrets safe.

```json
{
  "models": {
    "providers": {
      "google": {
        "baseUrl": "https://generativelanguage.googleapis.com/v1beta",
        "apiKey": "env:GOOGLE_API_KEY",
        "api": "google-generative-ai",
        "models": [
          {
            "id": "gemini-2.0-flash",
            "name": "Gemini 2.0 Flash",
            "reasoning": false,
            "input": ["text"],
            "contextWindow": 1000000,
            "maxTokens": 8192
          }
        ]
      }
    }
  },
  "agents": {
    "defaults": {
      "model": { "primary": "google/gemini-2.0-flash" },
      "workspace": "/app/agents",
      "maxConcurrent": 4
    }
  },
  "gateway": {
    "port": 18789,
    "mode": "local",
    "auth": {
      "mode": "token",
      "token": "env:CLAWDBOT_SECRET"
    }
  },
  "channels": {
    "telegram": {
      "enabled": true,
      "botToken": "env:TELEGRAM_BOT_TOKEN",
      "dmPolicy": "pairing",
      "groupPolicy": "allowlist"
    }
  }
}
```

## 3. Permissions Fix (Critical)

Docker often creates the `config/` directory as `root`, preventing the bot from writing to it. Fix ownership **before** starting.

```bash
# Fix ownership of the config directory (replace 1000:1000 with your user ID/GID if different)
docker run --rm -v ~/clawdbot-node/config:/config alpine chown -R 1000:1000 /config
```

## 4. Launch Service

Use `--force-recreate` to ensure new `.env` variables are picked up.

```bash
cd ~/clawdbot-node
docker compose up -d --force-recreate
```

## 5. Pairing

1. DM the bot (@YourBotName) with `/start` or any message.
2. It will reply: `Clawdbot: access not configured... Pairing code: XXXXXX`.
3. Run the approval command inside the container:

```bash
# Replace CODE with the 8-character code sent by the bot
docker compose exec clawdbot node dist/entry.js pairing approve telegram CODE
```

## 6. Verification

1. Send "Hello" to the bot.
2. If it replies, you are done.
3. If you get `LLM error: 429` / `Quota Exceeded`, verify your Google Cloud billing or switch API keys.
