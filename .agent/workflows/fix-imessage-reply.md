---
description: How to fix ClawdBot iMessage replying and permissions issues
---

# Troubleshooting iMessage Replying

If the bot stops replying to iMessages, follow these steps to resolve permission and filtering issues.

## 1. Full Disk Access (FDA)
The background service often loses access to the iMessage database.
1. Go to **System Settings** > **Privacy & Security** > **Full Disk Access**.
2. Ensure both of these are added and **ON**:
   - `/opt/homebrew/bin/node`
   - `/opt/homebrew/bin/imsg`
3. If not found, run `ls -l /opt/homebrew/bin/node /opt/homebrew/bin/imsg` in terminal to confirm paths.

## 2. Loopback Filter (Testing with Yourself)
The bot ignores messages marked `is_from_me` to prevent loops. To test from your own Mac/Phone to yourself, you must patch the whitelist.
1. Open `/opt/homebrew/lib/node_modules/clawdbot/dist/imessage/monitor/monitor-provider.js`.
2. Find the `is_from_me` check (around line 156).
3. Update it to include your handles:
   ```javascript
   if (message.is_from_me && !["your@email.com", "+123456789"].includes(senderNormalized))
       return;
   ```

## 3. Configuration Verification
Ensure `~/.clawdbot/clawdbot.json` has these critical fields:
- **Google Provider**: `baseUrl` must be `https://generativelanguage.googleapis.com/v1beta`.
- **Whitelisting**: `channels.imessage.allowFrom` should include `["*", "your@email.com", "+123456789"]`.

## 4. Forced Foreground Run
If background services (`launchctl`) fail due to permissions, run the gateway directly in your FDA-approved terminal:
```bash
CLAWDBOT_VERBOSE=1 clawdbot gateway
```
