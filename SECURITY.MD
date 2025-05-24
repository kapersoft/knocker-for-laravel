# Security Policy

## Supported Versions

We actively support the following versions of Knocker for Laravel with security updates:

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |

## Reporting a Vulnerability

We take the security of Knocker for Laravel seriously. If you discover a security vulnerability, please follow these steps:

### 1. Do NOT create a public GitHub issue

Security vulnerabilities should be reported privately to allow us to address them before they become public knowledge.

### 2. Send a detailed report

Please email security reports to: **<kapersoft@gmail.com>**

Include the following information in your report:

- **Description**: A clear description of the vulnerability
- **Impact**: What an attacker could achieve by exploiting this vulnerability
- **Reproduction**: Step-by-step instructions to reproduce the issue
- **Affected versions**: Which versions of the package are affected
- **Suggested fix**: If you have ideas for how to fix the issue (optional)

### 3. Response timeline

We are committed to responding to security reports promptly:

- **Initial response**: Within 48 hours of receiving your report
- **Status update**: Within 7 days with our assessment and planned timeline
- **Resolution**: We aim to release security fixes within 30 days for critical vulnerabilities

### 4. Coordinated disclosure

We follow responsible disclosure practices:

- We will work with you to understand and validate the vulnerability
- We will develop and test a fix
- We will prepare a security advisory
- We will coordinate the public disclosure timing with you
- We will credit you in the security advisory (unless you prefer to remain anonymous)

## Security Considerations

### Authentication Token Security

The Knocker for Laravel package uses authentication tokens to communicate with the [Knocker for Laravel Cloud](https://knocker.laravel.cloud) service. Please follow these security best practices:

#### Token Storage

- **Never commit tokens to version control**: Always use environment variables

#### Environment Configuration

✅ Good - Store in environment variables

```env
KNOCKER_TOKEN=your-secure-token-here
```

❌ Bad - Never hardcode in the `knocker-for-laravel.php` config file

```php
<?php

declare(strict_types=1);

return [
    'token' => 'your-secure-token-here',
];

```

### Network Security

#### HTTPS Communication

- All communication with Knocker services uses HTTPS by default
- Verify the `KNOCKER_ENDPOINT` uses HTTPS in production environments
- Do not disable SSL verification in production

#### Firewall Configuration

- Ensure your application can make outbound HTTPS requests to `knocker.laravel.cloud`
- Consider implementing IP allowlisting if your infrastructure supports it

### Data Privacy

#### Sensitive Information

The package transmits the following information to Knocker services:

- Scheduled task configurations (cron expressions, command names)
- Task descriptions and metadata
- Application timezone settings

**Important**: The package does NOT transmit:

- Database credentials or connection strings
- Environment variables (except timezone)
- Application source code
- User data or business logic

#### Data Minimization

- Review your scheduled task descriptions to ensure they don't contain sensitive information
- Avoid including sensitive data in command names or descriptions

### Production Deployment

#### Secure Configuration

```env
# .env file - Production environment configuration

# Required: Your Knocker authentication token
KNOCKER_TOKEN=your-secure-production-token-here

# Optional: Request timeout in seconds (recommended: 30-60 seconds)
KNOCKER_TIMEOUT=30

```

### Development Security

#### Local Development

- Use separate tokens for development and production environments
- Never use production tokens in development environments
- Consider using mock services for local testing

#### Testing

- Use test tokens or mock services in your test suite
- Ensure tests don't make real API calls to production services
- Validate that sensitive data is not logged during testing

## Security Updates

### Staying Informed

- Watch this repository for security updates
- Subscribe to our security advisories
- Follow [@kapersoft](https://github.com/kapersoft) for important announcements

### Updating Dependencies

- Keep the package updated to the latest version
- Regularly update Laravel and PHP to supported versions
- Monitor security advisories for Laravel and related packages

## Questions?

If you have questions about this security policy or need clarification on security best practices, please contact us at <kapersoft@gmail.com>.

---

**Last updated**: May 2025
