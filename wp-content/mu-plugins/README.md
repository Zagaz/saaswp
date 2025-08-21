# SAASWP MU Plugins

This folder contains must-use plugins that load automatically on every request.

## SMTP Enforcer

The `saaswp-smtp.php` MU-plugin forces WordPress to send mail via SMTP using environment variables.

Set these environment variables in production (e.g., Wasmer):

- SMTP_HOST (required)
- SMTP_PORT (optional; defaults to 587, or 465 if SMTP_SECURE=ssl)
- SMTP_SECURE (tls|ssl|starttls|none; default: tls)
- SMTP_USERNAME / SMTP_USER (optional)
- SMTP_PASSWORD / SMTP_PASS (optional)
- SMTP_FROM / MAIL_FROM (optional but recommended; e.g., no-reply@yourdomain)
- SMTP_FROM_NAME / MAIL_FROM_NAME (optional; e.g., Your App Name)
- SMTP_DEBUG (0-4; optional for troubleshooting)
- SMTP_TIMEOUT (seconds; optional)
- SMTP_ALLOW_SELF_SIGNED (true/false; optional)

Quick test (if WP-CLI is available):

wp saaswp smtp-test --to=email@example.com

## Provider examples

- SendGrid: SMTP_HOST=smtp.sendgrid.net, SMTP_PORT=587, SMTP_SECURE=tls, SMTP_USERNAME=apikey, SMTP_PASSWORD=<api_key>
- Mailgun: SMTP_HOST=smtp.mailgun.org, SMTP_PORT=587, SMTP_SECURE=tls, SMTP_USERNAME=postmaster@<domain>, SMTP_PASSWORD=<password>
- Postmark: SMTP_HOST=smtp.postmarkapp.com, SMTP_PORT=587, SMTP_SECURE=tls, SMTP_USERNAME=<server_token>, SMTP_PASSWORD=<server_token>
