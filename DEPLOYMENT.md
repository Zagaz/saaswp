# Deployment on Wasmer

This app expects SMTP to be configured via environment variables in production. We provide a must-use plugin at `wp-content/mu-plugins/saaswp-smtp.php` that routes all WordPress mail through SMTP.

## Required environment variables

Set the following in Wasmer project settings (Environment Variables):

- SMTP_HOST: SMTP server hostname (e.g., smtp.sendgrid.net)
- SMTP_PORT: 587 (TLS) or 465 (SSL). Optional when SMTP_SECURE provided
- SMTP_SECURE: tls | ssl | starttls | none (default: tls)
- SMTP_USERNAME: SMTP username (e.g., apikey for SendGrid)
- SMTP_PASSWORD: SMTP password or API key
- SMTP_FROM: no-reply@your-domain.tld
- SMTP_FROM_NAME: Your App Name

Optional:
- SMTP_DEBUG: 1-4 for troubleshooting (logs to PHP error_log)
- SMTP_TIMEOUT: seconds
- SMTP_ALLOW_SELF_SIGNED: true (only for testing)

## Testing email

If WP-CLI is available, use:

wp saaswp smtp-test --to=email@example.com

Otherwise, trigger a password reset or comment notification to verify.

## Notes

- PHP's native mail() is not reliable in serverless/edge environments; SMTP ensures deliverability.
- Keep credentials in Wasmer secrets, not in git or wp-config.php.
