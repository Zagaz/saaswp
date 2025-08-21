# SaaSWP Deployment on Wasmer

This project is a self-contained WordPress theme (SaaSWP). Mail is handled entirely by the theme.

## Mail configuration (Wasmer friendly)

Prefer HTTP API providers to avoid blocked SMTP ports on serverless hosts:

- Resend: set RESEND_API_KEY and SAASWP_MAIL_PROVIDER=resend
- Mailgun: set MAILGUN_API_KEY, MAILGUN_DOMAIN, optional MAILGUN_REGION=eu, and SAASWP_MAIL_PROVIDER=mailgun
- SendGrid: set SENDGRID_API_KEY and SAASWP_MAIL_PROVIDER=sendgrid

Optionally, configure the From identity:

- SAASWP_MAIL_FROM (email)
- SAASWP_MAIL_FROM_NAME (name)

SMTP fallback (not recommended on Wasmer):

- SAASWP_MAIL_PROVIDER=smtp
- SAASWP_SMTP_HOST, SAASWP_SMTP_PORT, SAASWP_SMTP_USER, SAASWP_SMTP_PASS, SAASWP_SMTP_SECURE=tls|ssl|none

Defaults if no env vars set: provider=php (native wp_mail/PHPMailer).

You can also configure these in wp-admin → Appearance → SaaSWP Mail. Env vars override dashboard values in production.

## Test email

After deployment, visit wp-admin → Appearance → SaaSWP Mail and send a test message.

## Notes

- The mu-plugin `saaswp-smtp.php` is intentionally empty; the theme fully manages email.
- Attachments, CC/BCC, and Reply-To are supported for Resend, Mailgun, and SendGrid.
