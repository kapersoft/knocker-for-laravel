# Contributing to Knocker for Laravel

Thank you for considering contributing to Knocker for Laravel! This document outlines the guidelines and processes for contributing to this project.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Setup](#development-setup)
- [Coding Standards](#coding-standards)
- [Testing](#testing)
- [Pull Request Process](#pull-request-process)
- [Issue Reporting](#issue-reporting)
- [Security Vulnerabilities](#security-vulnerabilities)

## Code of Conduct

This project adheres to a code of conduct that promotes a welcoming and inclusive environment. By participating, you are expected to uphold this standard. Please be respectful and professional in all interactions.

## Getting Started

### Prerequisites

Before contributing, ensure you have the following installed:

- **PHP 8.3 or higher**
- **Composer** (latest version)
- **Git**
- A **Laravel application** for testing (Laravel 12.0+)

### Fork and Clone

1. Fork the repository on GitHub
2. Clone your fork locally:

   ```bash
   git clone https://github.com/kapersoft/knocker-for-laravel.git
   cd knocker-for-laravel
   ```

## Development Setup

### Install Dependencies

```bash
composer install
```

### Development Tools

This project uses several development tools that are automatically configured:

- **Pest** - Testing framework
- **PHPStan** - Static analysis
- **Laravel Pint** - Code formatting
- **Rector** - Code refactoring and modernization

### Available Commands

```bash
# Run tests
composer test

# Run tests with coverage
composer test-coverage

# Run static analysis
composer analyse

# Format code
composer format

# Prepare package for development
composer prepare
```

## Coding Standards

### PHP Standards

- Follow **PSR-12** coding standards
- Use **strict types** declarations in all PHP files
- Write **type hints** for all method parameters and return types
- Use **meaningful variable and method names**

### Code Formatting

Code formatting is handled automatically by Laravel Pint. Run the formatter before committing:

```bash
composer format
```

### Static Analysis

All code must pass PHPStan analysis at the highest level. Run static analysis with:

```bash
composer analyse
```

### Architecture Guidelines

- Follow **SOLID principles**
- Use **dependency injection** where appropriate
- Keep classes **focused and single-purpose**
- Write **self-documenting code** with clear method names
- Add **PHPDoc blocks** for complex methods

## Testing

### Writing Tests

- All new features must include comprehensive tests
- Use **Pest** testing framework
- Follow the **AAA pattern** (Arrange, Act, Assert)
- Write both **unit tests** and **integration tests** where appropriate

### Test Structure

```php
<?php

use Kapersoft\Knocker\YourClass;

it('can perform the expected action', function () {
    // Arrange
    $instance = new YourClass();

    // Act
    $result = $instance->performAction();

    // Assert
    expect($result)->toBe('expected_value');
});
```

### Running Tests

```bash
# Run all tests
composer test

# Run tests with coverage report
composer test-coverage

# Run specific test file
vendor/bin/pest tests/YourTestFile.php
```

### Test Coverage

- Aim for **high test coverage** (90%+)
- All public methods should be tested
- Test both **happy paths** and **error conditions**

## Pull Request Process

### Before Submitting

1. **Create a feature branch** from `main`:

   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes** following the coding standards

3. **Run the full test suite**:

   ```bash
   composer test
   composer analyse
   composer format
   ```

4. **Update documentation** if necessary

5. **Add or update tests** for your changes

### Pull Request Guidelines

1. **Clear title and description** - Explain what your PR does and why
2. **Reference related issues** - Use "Fixes #123" or "Closes #123"
3. **Keep PRs focused** - One feature or fix per PR
4. **Update CHANGELOG.md** - Add your changes under "Unreleased"
5. **Ensure CI passes** - All GitHub Actions must be green

### PR Template

```markdown
## Description
Brief description of the changes made.

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Documentation update

## Testing
- [ ] Tests pass locally
- [ ] New tests added for new functionality
- [ ] Manual testing completed

## Checklist
- [ ] Code follows project coding standards
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] CHANGELOG.md updated
```

## Issue Reporting

### Bug Reports

When reporting bugs, please include:

- **Laravel version**
- **PHP version**
- **Package version**
- **Steps to reproduce**
- **Expected behavior**
- **Actual behavior**
- **Error messages** (if any)
- **Code samples** (if applicable)

### Feature Requests

For feature requests, please provide:

- **Clear description** of the proposed feature
- **Use case** - Why is this feature needed?
- **Proposed implementation** (if you have ideas)
- **Alternatives considered**

## Security Vulnerabilities

Please review [our security policy](SECURITY.MD) for information on reporting security vulnerabilities.

**Do not report security vulnerabilities through public GitHub issues.**

## Development Workflow

### Branching Strategy

- `main` - Production-ready code
- `feature/*` - New features
- `bugfix/*` - Bug fixes
- `hotfix/*` - Critical fixes

### Commit Messages

Use clear, descriptive commit messages:

```txt
feat: add support for custom cron expressions
fix: resolve timezone handling in scheduler tasks
docs: update installation instructions
test: add integration tests for command registration
```

### Release Process

Releases are handled by maintainers and follow semantic versioning:

- **MAJOR** - Breaking changes
- **MINOR** - New features (backward compatible)
- **PATCH** - Bug fixes (backward compatible)

## Getting Help

- **Documentation** - Check the README.md first
- **Issues** - Search existing issues before creating new ones
- **Discussions** - Use GitHub Discussions for questions
- **Email** - Contact [kapersoft@gmail.com](mailto:kapersoft@gmail.com) for private matters

## Recognition

Contributors will be recognized in:

- The project's README.md
- Release notes for significant contributions
- GitHub's contributor graph

Thank you for contributing to Knocker for Laravel! 🚀
