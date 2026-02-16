# Changelog

All notable changes to `shippo-php` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial release of the Shippo PHP SDK
- PSR-18 HTTP client abstraction
- Comprehensive exception hierarchy
- Strongly-typed DTOs for all API responses
- Support for all major Shippo API endpoints:
  - Addresses (create, retrieve, list, update, delete, validate)
  - Shipments (create, retrieve, list)
  - Rates (retrieve, list)
  - Transactions (create, retrieve, list)
  - Tracking (create, retrieve)
  - Parcels (create, retrieve, list)
  - Customs (items and declarations)
  - Refunds (create, retrieve, list)
  - Manifests (create, retrieve, list)
  - Carrier Accounts (create, retrieve, list, update)
  - Batches (create, add shipments, purchase, retrieve, list)
  - Orders (create, retrieve, list)
- Laravel integration with Service Provider and Facade
- Automatic retry logic with exponential backoff
- Pagination support
- Comprehensive test suite with Pest PHP
- PHPStan level 8 static analysis
- GitHub Actions CI/CD workflow
- Comprehensive documentation

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- N/A

## [1.0.0] - YYYY-MM-DD

Initial release.
