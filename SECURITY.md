# Security Policy

## Supported Versions

We release patches for security vulnerabilities for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.x.x   | :white_check_mark: |

## Reporting a Vulnerability

If you discover a security vulnerability within this package, please send an email to Igor Sazonov at sovletig@gmail.com. All security vulnerabilities will be promptly addressed.

Please do not publicly disclose the issue until it has been addressed by the team.

### What to Include

When reporting a vulnerability, please include:

- A description of the vulnerability
- Steps to reproduce the issue
- Potential impact
- Any suggested fixes (if applicable)

### Response Timeline

- **Initial Response**: Within 48 hours
- **Status Update**: Within 7 days
- **Fix Timeline**: Varies based on severity, but critical issues will be prioritized

## Security Best Practices

When using this SDK:

1. **Never commit API tokens** to version control
2. **Use environment variables** for sensitive configuration
3. **Use test tokens** in development environments
4. **Rotate API tokens** regularly
5. **Validate all user input** before passing to the SDK
6. **Keep the package updated** to the latest version
7. **Use HTTPS** for all API communications (enforced by default)

## Disclosure Policy

When a security vulnerability is reported and confirmed:

1. A fix will be developed and tested
2. A security advisory will be published
3. A new version will be released
4. Credit will be given to the reporter (unless anonymity is requested)

Thank you for helping keep Shippo PHP SDK and its users safe!
