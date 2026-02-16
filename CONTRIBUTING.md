# Contributing to Shippo PHP SDK

Thank you for considering contributing to the Shippo PHP SDK! This document outlines the process for contributing to this project.

## Code of Conduct

This project adheres to a code of conduct. By participating, you are expected to uphold this code. Please be respectful and constructive in all interactions.

## How to Contribute

### Reporting Bugs

If you find a bug, please create an issue on GitHub with:

- A clear, descriptive title
- Steps to reproduce the issue
- Expected behavior
- Actual behavior
- PHP version and package version
- Any relevant code samples or error messages

### Suggesting Enhancements

Enhancement suggestions are welcome! Please create an issue with:

- A clear, descriptive title
- Detailed explanation of the proposed feature
- Use cases and benefits
- Any relevant examples or mockups

### Pull Requests

1. **Fork the repository** and create your branch from `main`
2. **Install dependencies**: `composer install`
3. **Make your changes** following the coding standards below
4. **Add tests** for any new functionality
5. **Ensure all tests pass**: `composer test`
6. **Run static analysis**: `composer analyse`
7. **Commit your changes** with clear, descriptive commit messages
8. **Push to your fork** and submit a pull request

## Development Setup

```bash
# Clone your fork
git clone https://github.com/your-username/shippo-php.git
cd shippo-php

# Install dependencies
composer install

# Run tests
composer test

# Run static analysis
composer analyse
```

## Coding Standards

This project follows:

- **PSR-12** Extended Coding Style Guide
- **PSR-4** Autoloading Standard
- **Strict types** - All files must use `declare(strict_types=1);`
- **Type hints** - All parameters and return types must be typed
- **PHPDoc** - All public methods must have PHPDoc blocks

### Code Style

```php
<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Resources;

use Tigusigalpa\Shippo\DTOs\Address;

final class AddressResource extends BaseResource
{
    /**
     * Create a new address.
     *
     * @param array<string, mixed> $data
     * @return Address
     */
    public function create(array $data): Address
    {
        $response = $this->client->post('/addresses', $data);

        return Address::fromArray($response);
    }
}
```

## Testing Guidelines

- Write tests for all new features
- Maintain or improve code coverage
- Use descriptive test names
- Follow the Arrange-Act-Assert pattern

```php
test('address can be created from array', function () {
    // Arrange
    $data = [
        'object_id' => 'addr_123',
        'object_state' => 'VALID',
        'name' => 'John Doe',
    ];

    // Act
    $address = Address::fromArray($data);

    // Assert
    expect($address->objectId)->toBe('addr_123')
        ->and($address->name)->toBe('John Doe');
});
```

## Commit Message Guidelines

- Use the present tense ("Add feature" not "Added feature")
- Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
- Limit the first line to 72 characters or less
- Reference issues and pull requests liberally after the first line

Examples:
```
Add support for webhook verification
Fix rate limiting retry logic
Update documentation for Laravel 11
```

## Documentation

- Update README.md for any user-facing changes
- Add PHPDoc blocks for all public methods
- Include code examples for new features
- Keep documentation clear and concise

## Questions?

Feel free to create an issue for any questions about contributing!

## License

By contributing, you agree that your contributions will be licensed under the MIT License.
