# ClawdBot Configuration Notes (Working Setup)
**Date:** 2026-01-27
**Status:** ✅ Fully Functional

## 🏆 Winning Configuration
*   **Provider:** Google Gemini
*   **Model:** `google/gemini-2.5-pro`
    *   *Note:* Your specific API key DOES NOT have access to `gemini-1.5` series. It requires `2.5` or `2.0`.
    *   *Symptom of wrong model:* 404 Not Found error from API.
*   **API Key:** `AIza...WAZQ` (Stored in `~/.clawdbot/agents/main/agent/auth-profiles.json`)

## 🛠 Critical Troubleshooting Steps
If it stops working or you need to reinstall, follow these exact rules:

### 1. Model Selection
Always use **Gemini 2.5 Pro**.
Edit `~/.clawdbot/clawdbot.json`:
```json
"model": {
  "primary": "google/gemini-2.5-pro"
}
```

### 2. The "Silence" Fix (macOS Background Services)
Background services (like ClawdBot) often don't see variables from your terminal (`.zshrc`). You must force them:
```bash
# Force the key into the system launch agent
launchctl setenv GEMINI_API_KEY "YOUR_KEY_HERE"
launchctl setenv CLAWDBOT_GATEWAY_TOKEN "clawdbot-secret-token"
```
*Run this if the bot connects but never replies.*

### 3. Clearing "Ghosts" (Session Wipe)
If the bot thinks it's still using OpenAI or gets stuck:
```bash
# Wipes the bot's short-term memory
pkill -f clawdbot
rm -rf ~/.clawdbot/agents/main/sessions
clawdbot node restart
```

## 📂 Important Paths
*   **Global Config:** `~/.clawdbot/clawdbot.json`
*   **Auth/Keys:** `~/.clawdbot/agents/main/agent/auth-profiles.json`
*   **Logs:** `~/.clawdbot/logs/gateway.log` or `/tmp/clawdbot/*.log`
