# Custom Handlers Cookbook

This cookbook shows how to create and register custom postal code handlers.

## Overview

Custom handlers allow you to:
- Override default validation/formatting for existing countries
- Add support for countries not included in the package
- Implement specialized business logic for postal codes

## Creating a Custom Handler

Implement the `PostalCodeHandler` interface:

```php
<?php declare(strict_types=1);

namespace App\PostalCode;

use Cline\PostalCode\Contracts\PostalCodeHandler;

final class CustomXXHandler implements PostalCodeHandler
{
    public function validate(string $postalCode): bool
    {
        // Postal code is already normalized (uppercase, no spaces/hyphens)
        // Return true if valid, false otherwise
        return preg_match('/^[A-Z]{2}\d{4}$/', $postalCode) === 1;
    }

    public function format(string $postalCode): string
    {
        // Format the postal code for display
        // Input is already validated
        return substr($postalCode, 0, 2) . '-' . substr($postalCode, 2);
    }

    public function hint(): string
    {
        // Human-readable format description
        return 'Two letters followed by four digits (e.g., AB-1234)';
    }
}
```

## Extending AbstractHandler

For simpler handlers, extend the `AbstractHandler` base class:

```php
<?php declare(strict_types=1);

namespace App\PostalCode;

use Cline\PostalCode\Support\AbstractHandler;

final class CustomDEHandler extends AbstractHandler
{
    public function validate(string $postalCode): bool
    {
        // German postal codes: exactly 5 digits
        return preg_match('/^\d{5}$/', $postalCode) === 1;
    }

    public function hint(): string
    {
        return 'German postal codes are exactly 5 digits (e.g., 10115)';
    }

    // format() inherited from AbstractHandler - returns input unchanged
    // Override if custom formatting is needed
}
```

## Registering Custom Handlers

### Via Configuration File

Add handlers to `config/postal-code.php`:

```php
return [
    'handlers' => [
        'DE' => \App\PostalCode\CustomDEHandler::class,
        'XX' => \App\PostalCode\CustomCountryHandler::class,
    ],
];
```

### At Runtime

Register handlers programmatically:

```php
use Cline\PostalCode\Facades\PostalCode;

PostalCode::registerHandler('XX', \App\PostalCode\CustomXXHandler::class);

// The method is chainable
PostalCode::registerHandler('YY', \App\PostalCode\YYHandler::class)
          ->registerHandler('ZZ', \App\PostalCode\ZZHandler::class);

// Now use your custom handler
$formatted = PostalCode::format('XX', 'AB1234'); // "AB-1234"
```

## Example: Override US ZIP Code Handler

Create a stricter US handler that only accepts 5-digit ZIP codes:

```php
<?php declare(strict_types=1);

namespace App\PostalCode;

use Cline\PostalCode\Contracts\PostalCodeHandler;

final class StrictUSHandler implements PostalCodeHandler
{
    public function validate(string $postalCode): bool
    {
        // Only accept 5-digit ZIP codes, reject ZIP+4
        return preg_match('/^\d{5}$/', $postalCode) === 1;
    }

    public function format(string $postalCode): string
    {
        return $postalCode;
    }

    public function hint(): string
    {
        return 'US ZIP codes must be exactly 5 digits';
    }
}
```

Register it:

```php
// config/postal-code.php
return [
    'handlers' => [
        'US' => \App\PostalCode\StrictUSHandler::class,
    ],
];
```

## Example: Add Support for Fictional Country

```php
<?php declare(strict_types=1);

namespace App\PostalCode;

use Cline\PostalCode\Support\AbstractHandler;

/**
 * Handler for Wakanda (fictional country).
 * Format: 3 letters + 3 digits (e.g., WAK-123)
 */
final class WAHandler extends AbstractHandler
{
    public function validate(string $postalCode): bool
    {
        return preg_match('/^[A-Z]{3}\d{3}$/', $postalCode) === 1;
    }

    public function format(string $postalCode): string
    {
        // Add hyphen between letters and digits
        return substr($postalCode, 0, 3) . '-' . substr($postalCode, 3);
    }

    public function hint(): string
    {
        return 'Wakandan postal codes: 3 letters, hyphen, 3 digits (e.g., WAK-123)';
    }
}
```

## Handler Resolution Order

When resolving a handler for a country:

1. **Custom handlers** (from config or `registerHandler()`) are checked first
2. **Default handlers** (in `src/Handlers/`) are used as fallback
3. If no handler exists, `UnknownCountryException` is thrown

## Important Notes

- **Input is pre-normalized**: Postal codes passed to handlers are already uppercase with spaces/hyphens removed
- **Stateless handlers**: Handlers are instantiated without constructor arguments
- **Handler caching**: Handlers are cached after first use. Use `registerHandler()` to replace cached handlers
- **Country codes**: Use ISO 3166-1 alpha-2 codes (2 uppercase letters)
