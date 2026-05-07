# Crisp customer chat (storefront)

## Setup

1. Create a workspace at [crisp.chat](https://crisp.chat) and add your site.
2. Copy the **Website ID** (Crisp → Settings → Website → Setup / Website ID).
3. Set in `.env`:
   - `CRISP_WEBSITE_ID=<your-website-id>`
4. Deploy or clear config cache: `php artisan config:clear`.

The Shop layout loads the widget when `CRISP_WEBSITE_ID` is set. Logged-in customers pass email and display name to Crisp so agents see context.

## Operators (mobile and web)

- Install **Crisp** from the iOS/Android app store and sign in with the same workspace.
- Open the inbox to reply to conversations from the storefront widget.

## Chatbot and automation

Configure bots, auto-responders, and FAQs in the Crisp dashboard (Automation / Chatbot). No extra code is required for basic flows; hand off to humans in the same inbox when needed.

## Content Security Policy

If you add a strict CSP, allow scripts from `https://client.crisp.chat` and WebSocket connections used by Crisp.
