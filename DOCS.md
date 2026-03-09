## Table of Contents

1. [Basic Usage](#doc-cookbooks-basic-usage) (`cookbooks/basic-usage.md`)
2. [Custom Handlers](#doc-cookbooks-custom-handlers) (`cookbooks/custom-handlers.md`)
3. [Exception Handling](#doc-cookbooks-exception-handling) (`cookbooks/exception-handling.md`)
4. [Laravel Integration](#doc-cookbooks-laravel-integration) (`cookbooks/laravel-integration.md`)
5. [Overview](#doc-docs-readme) (`docs/README.md`)
6. [Basic Usage](#doc-docs-basic-usage) (`docs/basic-usage.md`)
7. [Countries Without Postcodes](#doc-docs-countries-without-postcodes) (`docs/countries-without-postcodes.md`)
8. [Custom Handlers](#doc-docs-custom-handlers) (`docs/custom-handlers.md`)
9. [Exception Handling](#doc-docs-exception-handling) (`docs/exception-handling.md`)
10. [Laravel Integration](#doc-docs-laravel-integration) (`docs/laravel-integration.md`)
<a id="doc-cookbooks-basic-usage"></a>

# Basic Usage Cookbook

This cookbook covers the essential operations for validating and formatting postal codes.

## Quick Start

```php
use Cline\PostalCode\Facades\PostalCode;

// Validate a postal code
$isValid = PostalCode::validate('US', '12345'); // true
$isValid = PostalCode::validate('US', 'ABC'); // false

// Format a postal code
$formatted = PostalCode::format('US', '123456789'); // "12345-6789"
```

## Using the Fluent Interface

The `for()` method creates a `PostalCode` value object with a chainable API:

```php
use Cline\PostalCode\Facades\PostalCode;

$postal = PostalCode::for('US', '12345-6789');

// Check validity
if ($postal->isValid()) {
    echo $postal->format(); // "12345-6789"
}

// Get format hint for user feedback
echo $postal->hint(); // "PostalCodes in the USA are called ZIP codes."

// Access the original and normalized values
echo $postal->original(); // "12345-6789"
echo $postal->normalized(); // "123456789"

// Get country code
echo $postal->country(); // "US"
```

## Validation

### Basic Validation

```php
use Cline\PostalCode\Facades\PostalCode;

// Validate using the manager directly
if (PostalCode::validate('CA', 'K1A 0B1')) {
    echo 'Valid Canadian postal code!';
}

// Validate using the fluent interface
$postal = PostalCode::for('GB', 'SW1A 1AA');
if ($postal->isValid()) {
    echo 'Valid UK postal code!';
}
```

### Check Country Support

```php
use Cline\PostalCode\Facades\PostalCode;

// Via manager
if (PostalCode::isSupportedCountry('DE')) {
    echo 'Germany is supported!';
}

// Via fluent interface
$postal = PostalCode::for('ZZ', '12345');
if (!$postal->isCountrySupported()) {
    echo 'Country not supported';
}
```

## Formatting

### Format with Exception on Invalid

```php
use Cline\PostalCode\Facades\PostalCode;
use Cline\PostalCode\Exceptions\InvalidPostalCodeException;

try {
    $formatted = PostalCode::format('US', '123456789');
    echo $formatted; // "12345-6789"
} catch (InvalidPostalCodeException $e) {
    echo 'Invalid: ' . $e->getMessage();
}
```

### Format with Null Fallback

```php
use Cline\PostalCode\Facades\PostalCode;

// Returns null instead of throwing on invalid
$formatted = PostalCode::formatOrNull('US', 'invalid');
if ($formatted === null) {
    echo 'Could not format postal code';
}
```

### Format with Default Value

```php
use Cline\PostalCode\Facades\PostalCode;

// Via fluent interface - returns default if invalid
$postal = PostalCode::for('US', 'invalid');
echo $postal->formatOr('N/A'); // "N/A"
```

## String Conversion

The `PostalCode` value object implements `Stringable`:

```php
use Cline\PostalCode\Facades\PostalCode;

$postal = PostalCode::for('US', '123456789');

// Automatic string conversion returns formatted value
echo $postal; // "12345-6789"
echo (string) $postal; // "12345-6789"

// Use in string contexts
$message = "Your ZIP code is: {$postal}";
```

## Format Hints

Get human-readable format descriptions for validation feedback:

```php
use Cline\PostalCode\Facades\PostalCode;

// Via manager
$hint = PostalCode::getHint('US');
echo $hint; // "PostalCodes in the USA are called ZIP codes."

// Via fluent interface
$postal = PostalCode::for('CA', 'invalid');
echo $postal->hint(); // Provides Canadian format hint
```

## Input Normalization

Postal codes are automatically normalized (uppercase, separators removed):

```php
use Cline\PostalCode\Facades\PostalCode;

// All of these are valid and equivalent
PostalCode::validate('CA', 'k1a 0b1'); // true
PostalCode::validate('CA', 'K1A-0B1'); // true
PostalCode::validate('CA', 'K1A0B1'); // true

// Check normalization
$postal = PostalCode::for('CA', 'k1a 0b1');
echo $postal->original(); // "k1a 0b1"
echo $postal->normalized(); // "K1A0B1"
echo $postal->format(); // "K1A 0B1"
```

## Supported Countries

The package includes handlers for 180+ countries. Use `isSupportedCountry()` to check support:

```php
use Cline\PostalCode\Facades\PostalCode;

$countries = ['US', 'CA', 'GB', 'DE', 'FR', 'AU', 'JP'];

foreach ($countries as $country) {
    if (PostalCode::isSupportedCountry($country)) {
        echo "{$country}: Supported\n";
    }
}
```

<a id="doc-cookbooks-custom-handlers"></a>

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

<a id="doc-cookbooks-exception-handling"></a>

# Exception Handling Cookbook

This cookbook covers how to handle exceptions when working with postal code validation and formatting.

## Exception Types

The package provides two exception types, both implementing `PostalCodeException`:

| Exception | When Thrown |
|-----------|-------------|
| `InvalidPostalCodeException` | Postal code fails validation for the country |
| `UnknownCountryException` | Country code is not supported |

## Handling InvalidPostalCodeException

Thrown when a postal code doesn't match the country's format:

```php
use Cline\PostalCode\Facades\PostalCode;
use Cline\PostalCode\Exceptions\InvalidPostalCodeException;

try {
    $formatted = PostalCode::format('US', 'invalid-code');
} catch (InvalidPostalCodeException $e) {
    echo $e->getMessage();
    // "Invalid postalCode: INVALIDCODE. PostalCodes in the USA are called ZIP codes."

    // Access exception details
    echo $e->getPostalCode(); // "INVALIDCODE"
    echo $e->getCountry();    // "US"
    echo $e->getHint();       // "PostalCodes in the USA are called ZIP codes."
}
```

## Handling UnknownCountryException

Thrown when using an unsupported country code:

```php
use Cline\PostalCode\Facades\PostalCode;
use Cline\PostalCode\Exceptions\UnknownCountryException;

try {
    $formatted = PostalCode::format('ZZ', '12345');
} catch (UnknownCountryException $e) {
    echo $e->getMessage();  // "Unknown country: ZZ"
    echo $e->getCountry();  // "ZZ"
}
```

## Catching All Postal Code Exceptions

Use the marker interface to catch any postal code exception:

```php
use Cline\PostalCode\Facades\PostalCode;
use Cline\PostalCode\Contracts\PostalCodeException;

try {
    $formatted = PostalCode::format($country, $postalCode);
} catch (PostalCodeException $e) {
    // Catches both InvalidPostalCodeException and UnknownCountryException
    echo 'Postal code error: ' . $e->getMessage();
}
```

## Avoiding Exceptions

### Check Country Support First

```php
use Cline\PostalCode\Facades\PostalCode;

if (!PostalCode::isSupportedCountry($country)) {
    echo "Country {$country} is not supported";
    return;
}

// Safe to call - country is supported
$isValid = PostalCode::validate($country, $postalCode);
```

### Use formatOrNull() Instead of format()

```php
use Cline\PostalCode\Facades\PostalCode;

// Returns null instead of throwing InvalidPostalCodeException
$formatted = PostalCode::formatOrNull('US', $userInput);

if ($formatted === null) {
    echo 'Invalid postal code format';
} else {
    echo "Formatted: {$formatted}";
}
```

### Use formatOr() for Default Values

```php
use Cline\PostalCode\Facades\PostalCode;

$postal = PostalCode::for('US', $userInput);
$formatted = $postal->formatOr('N/A');

echo "ZIP: {$formatted}"; // Either formatted code or "N/A"
```

### Validate Before Formatting

```php
use Cline\PostalCode\Facades\PostalCode;

$postal = PostalCode::for('US', $userInput);

if ($postal->isValid()) {
    echo $postal->format(); // Safe - won't throw
} else {
    echo "Invalid: " . $postal->hint();
}
```

## Exception Handling Patterns

### Form Validation

```php
use Cline\PostalCode\Facades\PostalCode;
use Cline\PostalCode\Contracts\PostalCodeException;

function validatePostalCode(string $country, string $postalCode): array
{
    $errors = [];

    if (!PostalCode::isSupportedCountry($country)) {
        $errors['country'] = "Country '{$country}' is not supported";
        return ['valid' => false, 'errors' => $errors];
    }

    $postal = PostalCode::for($country, $postalCode);

    if (!$postal->isValid()) {
        $errors['postal_code'] = $postal->hint();
        return ['valid' => false, 'errors' => $errors];
    }

    return [
        'valid' => true,
        'formatted' => $postal->format(),
        'errors' => [],
    ];
}
```

### API Response

```php
use Cline\PostalCode\Facades\PostalCode;
use Cline\PostalCode\Exceptions\InvalidPostalCodeException;
use Cline\PostalCode\Exceptions\UnknownCountryException;

function formatPostalCodeResponse(string $country, string $postalCode): array
{
    try {
        return [
            'success' => true,
            'formatted' => PostalCode::format($country, $postalCode),
        ];
    } catch (UnknownCountryException $e) {
        return [
            'success' => false,
            'error' => 'unsupported_country',
            'message' => "Country '{$e->getCountry()}' is not supported",
        ];
    } catch (InvalidPostalCodeException $e) {
        return [
            'success' => false,
            'error' => 'invalid_postal_code',
            'message' => $e->getMessage(),
            'hint' => $e->getHint(),
        ];
    }
}
```

### Batch Processing

```php
use Cline\PostalCode\Facades\PostalCode;
use Cline\PostalCode\Contracts\PostalCodeException;

$records = [
    ['country' => 'US', 'postal' => '12345'],
    ['country' => 'ZZ', 'postal' => '00000'],
    ['country' => 'CA', 'postal' => 'K1A0B1'],
];

$results = [];
$errors = [];

foreach ($records as $index => $record) {
    try {
        $results[$index] = PostalCode::format($record['country'], $record['postal']);
    } catch (PostalCodeException $e) {
        $errors[$index] = $e->getMessage();
    }
}

echo "Processed: " . count($results);
echo "Errors: " . count($errors);
```

## Exception Properties Reference

### InvalidPostalCodeException

| Method | Returns | Description |
|--------|---------|-------------|
| `getMessage()` | `string` | Full error message with hint |
| `getPostalCode()` | `string` | The invalid postal code (normalized) |
| `getCountry()` | `string` | The country code |
| `getHint()` | `?string` | Format hint, or null |

### UnknownCountryException

| Method | Returns | Description |
|--------|---------|-------------|
| `getMessage()` | `string` | "Unknown country: XX" |
| `getCountry()` | `string` | The unsupported country code |

<a id="doc-cookbooks-laravel-integration"></a>

# Laravel Integration Cookbook

This cookbook covers Laravel-specific features and integration patterns.

## Installation

```bash
composer require cline/postal-code
```

The service provider is auto-discovered. No manual registration required.

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=postal-code-config
```

This creates `config/postal-code.php`:

```php
return [
    'handlers' => [
        // Register custom handlers here
        // 'XX' => \App\PostalCode\CustomHandler::class,
    ],
];
```

## Using the Facade

Import the facade for convenient static access:

```php
use Cline\PostalCode\Facades\PostalCode;

// Validate
$isValid = PostalCode::validate('US', '12345');

// Format
$formatted = PostalCode::format('CA', 'K1A0B1'); // "K1A 0B1"

// Fluent interface
$postal = PostalCode::for('GB', 'SW1A 1AA');
```

## Dependency Injection

Inject `PostalCodeManager` where needed:

```php
use Cline\PostalCode\PostalCodeManager;

class AddressController extends Controller
{
    public function __construct(
        private PostalCodeManager $postalCodes,
    ) {}

    public function store(Request $request)
    {
        $postal = $this->postalCodes->for(
            $request->country,
            $request->postal_code
        );

        if (!$postal->isValid()) {
            return back()->withErrors([
                'postal_code' => $postal->hint(),
            ]);
        }

        // Store the formatted postal code
        $address = Address::create([
            'postal_code' => $postal->format(),
        ]);
    }
}
```

## Form Request Validation

Create a custom validation rule:

```php
<?php declare(strict_types=1);

namespace App\Rules;

use Closure;
use Cline\PostalCode\Facades\PostalCode;
use Illuminate\Contracts\Validation\ValidationRule;

final class ValidPostalCode implements ValidationRule
{
    public function __construct(
        private string $countryField = 'country',
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $country = request($this->countryField);

        if (!PostalCode::isSupportedCountry($country)) {
            $fail("Country '{$country}' is not supported for postal code validation.");
            return;
        }

        if (!PostalCode::validate($country, $value)) {
            $hint = PostalCode::getHint($country);
            $fail("The {$attribute} format is invalid. {$hint}");
        }
    }
}
```

Use in a Form Request:

```php
use App\Rules\ValidPostalCode;

class StoreAddressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'country' => ['required', 'string', 'size:2'],
            'postal_code' => ['required', 'string', new ValidPostalCode('country')],
        ];
    }
}
```

## Model Casting

Create a custom cast for automatic formatting:

```php
<?php declare(strict_types=1);

namespace App\Casts;

use Cline\PostalCode\Facades\PostalCode;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

final class PostalCodeCast implements CastsAttributes
{
    public function __construct(
        private string $countryAttribute = 'country',
    ) {}

    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        $country = $attributes[$this->countryAttribute] ?? null;

        if ($country === null || !PostalCode::isSupportedCountry($country)) {
            return $value;
        }

        return PostalCode::formatOrNull($country, $value) ?? $value;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        // Store normalized value (uppercase, no separators)
        return mb_strtoupper(str_replace([' ', '-'], '', $value));
    }
}
```

Use on a model:

```php
use App\Casts\PostalCodeCast;

class Address extends Model
{
    protected $casts = [
        'postal_code' => PostalCodeCast::class . ':country',
    ];
}
```

## Blade Components

Create a reusable postal code input:

```php
// resources/views/components/postal-code-input.blade.php
@props([
    'name' => 'postal_code',
    'country' => null,
    'value' => null,
])

@php
    use Cline\PostalCode\Facades\PostalCode;
    $hint = $country && PostalCode::isSupportedCountry($country)
        ? PostalCode::getHint($country)
        : null;
@endphp

<div {{ $attributes->merge(['class' => 'form-group']) }}>
    <label for="{{ $name }}">Postal Code</label>
    <input
        type="text"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        @if($hint) placeholder="{{ $hint }}" @endif
        class="form-control @error($name) is-invalid @enderror"
    >
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
```

## API Resources

Format postal codes in API responses:

```php
use Cline\PostalCode\Facades\PostalCode;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'street' => $this->street,
            'city' => $this->city,
            'country' => $this->country,
            'postal_code' => $this->getFormattedPostalCode(),
        ];
    }

    private function getFormattedPostalCode(): ?string
    {
        if (!$this->postal_code || !$this->country) {
            return $this->postal_code;
        }

        return PostalCode::formatOrNull($this->country, $this->postal_code)
            ?? $this->postal_code;
    }
}
```

## Livewire Integration

```php
use Cline\PostalCode\Facades\PostalCode;
use Livewire\Component;

class AddressForm extends Component
{
    public string $country = '';
    public string $postalCode = '';
    public ?string $postalCodeHint = null;
    public ?string $formattedPostalCode = null;

    public function updatedCountry(): void
    {
        $this->postalCodeHint = PostalCode::isSupportedCountry($this->country)
            ? PostalCode::getHint($this->country)
            : null;

        $this->validatePostalCode();
    }

    public function updatedPostalCode(): void
    {
        $this->validatePostalCode();
    }

    private function validatePostalCode(): void
    {
        if (!$this->country || !$this->postalCode) {
            $this->formattedPostalCode = null;
            return;
        }

        if (!PostalCode::isSupportedCountry($this->country)) {
            $this->formattedPostalCode = $this->postalCode;
            return;
        }

        $this->formattedPostalCode = PostalCode::formatOrNull(
            $this->country,
            $this->postalCode
        );
    }

    public function render()
    {
        return view('livewire.address-form');
    }
}
```

## Testing

```php
use Cline\PostalCode\Facades\PostalCode;

test('validates US ZIP code', function () {
    expect(PostalCode::validate('US', '12345'))->toBeTrue();
    expect(PostalCode::validate('US', 'ABCDE'))->toBeFalse();
});

test('formats Canadian postal code', function () {
    expect(PostalCode::format('CA', 'K1A0B1'))->toBe('K1A 0B1');
});

test('fluent interface works', function () {
    $postal = PostalCode::for('US', '123456789');

    expect($postal->isValid())->toBeTrue();
    expect($postal->format())->toBe('12345-6789');
    expect($postal->country())->toBe('US');
});
```

## Service Container Notes

- `PostalCodeManager` is registered as a singleton via PHP 8 attributes
- The manager is resolved from the container with config injection
- Custom handlers from config are loaded automatically

<a id="doc-docs-readme"></a>

Postal Code is a PHP library for validating postal/ZIP codes for countries worldwide.

## Installation

```bash
composer require cline/postal-code
```

## Basic Usage

```php
use Cline\PostalCode\PostalCode;

// Validate a postal code
$result = PostalCode::validate('90210', 'US');
$result->isValid(); // true

// UK postal code
$result = PostalCode::validate('SW1A 1AA', 'GB');
$result->isValid(); // true

// German postal code
$result = PostalCode::validate('10115', 'DE');
$result->isValid(); // true
```

## Quick Validation

```php
use Cline\PostalCode\PostalCode;

// Returns boolean
PostalCode::isValid('90210', 'US'); // true
PostalCode::isValid('invalid', 'US'); // false

// With exception on invalid
PostalCode::validateOrFail('90210', 'US'); // Returns result
PostalCode::validateOrFail('invalid', 'US'); // Throws exception
```

## Supported Countries

```php
use Cline\PostalCode\PostalCode;

// Get all supported country codes
$countries = PostalCode::getSupportedCountries();
// ["AD", "AE", "AF", "AG", ...]

// Check if country is supported
PostalCode::isCountrySupported('US'); // true
PostalCode::isCountrySupported('XX'); // false
```

## Formatting

```php
$result = PostalCode::validate('sw1a1aa', 'GB');

// Get formatted version
$result->formatted(); // "SW1A 1AA"

// Original input
$result->input(); // "sw1a1aa"
```

## Next Steps

- [Basic Usage](#doc-docs-basic-usage) - Validation patterns
- [Custom Handlers](#doc-docs-custom-handlers) - Add custom validators
- [Exception Handling](#doc-docs-exception-handling) - Handle validation errors
- [Laravel Integration](#doc-docs-laravel-integration) - Use with Laravel

<a id="doc-docs-basic-usage"></a>

Validating and formatting postal codes for different countries.

## Validation

```php
use Cline\PostalCode\PostalCode;

// Basic validation
$result = PostalCode::validate('90210', 'US');

if ($result->isValid()) {
    echo "Valid: " . $result->formatted();
} else {
    echo "Invalid: " . $result->error();
}
```

## Country-Specific Examples

### United States

```php
// 5-digit ZIP
PostalCode::isValid('90210', 'US'); // true

// ZIP+4
PostalCode::isValid('90210-1234', 'US'); // true

// Invalid
PostalCode::isValid('9021', 'US'); // false (too short)
PostalCode::isValid('902101', 'US'); // false (too long)
```

### United Kingdom

```php
// Various UK formats
PostalCode::isValid('SW1A 1AA', 'GB'); // true
PostalCode::isValid('M1 1AE', 'GB'); // true
PostalCode::isValid('B33 8TH', 'GB'); // true
PostalCode::isValid('CR2 6XH', 'GB'); // true
PostalCode::isValid('DN55 1PT', 'GB'); // true

// Case insensitive
PostalCode::isValid('sw1a 1aa', 'GB'); // true
```

### Germany

```php
// 5-digit postal codes
PostalCode::isValid('10115', 'DE'); // true (Berlin)
PostalCode::isValid('80331', 'DE'); // true (Munich)

// Invalid
PostalCode::isValid('1011', 'DE'); // false
PostalCode::isValid('101155', 'DE'); // false
```

### Canada

```php
// Format: A1A 1A1
PostalCode::isValid('K1A 0B1', 'CA'); // true
PostalCode::isValid('M5V 2T6', 'CA'); // true

// Without space
PostalCode::isValid('K1A0B1', 'CA'); // true
```

### Other Countries

```php
// France (5 digits)
PostalCode::isValid('75001', 'FR'); // true

// Netherlands (4 digits + 2 letters)
PostalCode::isValid('1012 AB', 'NL'); // true

// Japan (7 digits with hyphen)
PostalCode::isValid('100-0001', 'JP'); // true

// Australia (4 digits)
PostalCode::isValid('2000', 'AU'); // true

// Brazil (8 digits with hyphen)
PostalCode::isValid('01310-100', 'BR'); // true
```

## Formatting

```php
// Auto-format based on country rules
$result = PostalCode::validate('sw1a1aa', 'GB');
$result->formatted(); // "SW1A 1AA"

$result = PostalCode::validate('k1a0b1', 'CA');
$result->formatted(); // "K1A 0B1"

// Get without formatting
$result->input(); // Original input
$result->normalized(); // Cleaned but not formatted
```

## Validation Result

```php
$result = PostalCode::validate('90210', 'US');

// Check validity
$result->isValid(); // true

// Get formatted value
$result->formatted(); // "90210"

// Get country
$result->country(); // "US"

// For invalid codes
$result = PostalCode::validate('invalid', 'US');
$result->isValid(); // false
$result->error(); // "Invalid postal code format for US"
```

## Batch Validation

```php
$postalCodes = ['90210', '10001', 'invalid', '94102'];

$results = array_map(
    fn($code) => [
        'code' => $code,
        'valid' => PostalCode::isValid($code, 'US'),
    ],
    $postalCodes
);
```

<a id="doc-docs-countries-without-postcodes"></a>

Some countries do not have postal code systems. When shipping to these destinations or validating addresses, you may need to detect this and provide a fallback value.

## Checking Postal Code Support

Use the `Country` helper to check if a country uses postal codes:

```php
use Cline\PostalCode\Support\Country;

// Countries with postal codes
Country::hasPostalCode('US'); // true
Country::hasPostalCode('GB'); // true
Country::hasPostalCode('DE'); // true

// Countries without postal codes
Country::hasPostalCode('HK'); // false (Hong Kong)
Country::hasPostalCode('AE'); // false (United Arab Emirates)
Country::hasPostalCode('QA'); // false (Qatar)
```

The method is case-insensitive:

```php
Country::hasPostalCode('hk'); // false
Country::hasPostalCode('Hk'); // false
```

## Fallback Value

When a country doesn't use postal codes but your system requires one (e.g., for shipping carriers), use the fallback:

```php
use Cline\PostalCode\Support\Country;

$postalCode = Country::hasPostalCode($countryCode)
    ? $userProvidedPostalCode
    : Country::fallbackPostalCode(); // Returns '00000'
```

## Practical Example

Here's how to normalize postal codes in a shipping application:

```php
use Cline\PostalCode\Facades\PostalCode;
use Cline\PostalCode\Support\Country;

function normalizePostalCode(string $postalCode, string $countryCode): string
{
    // Countries without postal codes get the fallback
    if (!Country::hasPostalCode($countryCode)) {
        return Country::fallbackPostalCode();
    }

    // Format valid postal codes, return original if invalid
    return PostalCode::for($postalCode, $countryCode)->formatOr($postalCode);
}

// Usage
normalizePostalCode('90210', 'US');      // '90210'
normalizePostalCode('WC2E9RZ', 'GB');    // 'WC2E 9RZ'
normalizePostalCode('anything', 'HK');   // '00000'
normalizePostalCode('anything', 'QA');   // '00000'
```

## Complete List

Get all countries without postal code systems:

```php
use Cline\PostalCode\Support\Country;

$countries = Country::countriesWithoutPostalCodes();
// ['AE', 'AG', 'AN', 'AO', 'AW', 'BF', 'BI', ...]
```

### Countries Without Postal Codes

| Code | Country |
|------|---------|
| AE | United Arab Emirates |
| AG | Antigua and Barbuda |
| AN | Netherlands Antilles (former) |
| AO | Angola |
| AW | Aruba |
| BF | Burkina Faso |
| BI | Burundi |
| BJ | Benin |
| BO | Bolivia |
| BS | Bahamas |
| BW | Botswana |
| BZ | Belize |
| CD | Democratic Republic of the Congo |
| CF | Central African Republic |
| CG | Republic of the Congo |
| CI | Ivory Coast |
| CK | Cook Islands |
| CM | Cameroon |
| CW | Curacao |
| DJ | Djibouti |
| DM | Dominica |
| ER | Eritrea |
| FJ | Fiji |
| GA | Gabon |
| GD | Grenada |
| GH | Ghana |
| GM | Gambia |
| GQ | Equatorial Guinea |
| GY | Guyana |
| HK | Hong Kong |
| JM | Jamaica |
| KI | Kiribati |
| KM | Comoros |
| KN | Saint Kitts and Nevis |
| KP | North Korea |
| ML | Mali |
| MO | Macau |
| MR | Mauritania |
| MW | Malawi |
| NR | Nauru |
| NU | Niue |
| QA | Qatar |
| RW | Rwanda |
| SB | Solomon Islands |
| SC | Seychelles |
| SL | Sierra Leone |
| SO | Somalia |
| SR | Suriname |
| SS | South Sudan |
| ST | Sao Tome and Principe |
| SX | Sint Maarten |
| SY | Syria |
| TD | Chad |
| TG | Togo |
| TK | Tokelau |
| TL | Timor-Leste |
| TO | Tonga |
| TV | Tuvalu |
| UG | Uganda |
| VU | Vanuatu |
| YE | Yemen |
| ZW | Zimbabwe |

<a id="doc-docs-custom-handlers"></a>

Create custom postal code validators for specific needs.

## Creating a Custom Handler

```php
use Cline\PostalCode\Contracts\PostalCodeHandler;
use Cline\PostalCode\PostalCode;

class CustomCountryHandler implements PostalCodeHandler
{
    public function validate(string $postalCode): bool
    {
        // Custom validation logic
        return preg_match('/^[A-Z]{2}\d{4}$/', $postalCode) === 1;
    }

    public function format(string $postalCode): string
    {
        // Custom formatting
        return strtoupper($postalCode);
    }

    public function getPattern(): string
    {
        return '/^[A-Z]{2}\d{4}$/';
    }
}

// Register the handler
PostalCode::extend('XX', new CustomCountryHandler());

// Use it
PostalCode::isValid('AB1234', 'XX'); // true
```

## Handler Interface

```php
interface PostalCodeHandler
{
    /**
     * Validate a postal code.
     */
    public function validate(string $postalCode): bool;

    /**
     * Format a postal code.
     */
    public function format(string $postalCode): string;

    /**
     * Get the regex pattern for validation.
     */
    public function getPattern(): string;
}
```

## Extending Existing Handlers

```php
use Cline\PostalCode\Handlers\USPostalCodeHandler;

class StrictUSHandler extends USPostalCodeHandler
{
    public function validate(string $postalCode): bool
    {
        // Call parent validation
        if (!parent::validate($postalCode)) {
            return false;
        }

        // Add custom rules (e.g., check against database)
        return $this->existsInDatabase($postalCode);
    }

    private function existsInDatabase(string $postalCode): bool
    {
        // Check if ZIP exists in your database
        return DB::table('zip_codes')
            ->where('code', $postalCode)
            ->exists();
    }
}

// Register
PostalCode::extend('US', new StrictUSHandler());
```

## Multiple Patterns

```php
class MultiPatternHandler implements PostalCodeHandler
{
    private array $patterns = [
        '/^\d{5}$/',           // 5 digits
        '/^\d{5}-\d{4}$/',     // ZIP+4
        '/^\d{9}$/',           // 9 digits without hyphen
    ];

    public function validate(string $postalCode): bool
    {
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $postalCode)) {
                return true;
            }
        }
        return false;
    }

    public function format(string $postalCode): string
    {
        // Normalize to ZIP+4 format if 9 digits
        if (preg_match('/^\d{9}$/', $postalCode)) {
            return substr($postalCode, 0, 5) . '-' . substr($postalCode, 5);
        }
        return $postalCode;
    }

    public function getPattern(): string
    {
        return '/^\d{5}(-\d{4})?$/';
    }
}
```

## Conditional Handlers

```php
class ConditionalHandler implements PostalCodeHandler
{
    public function __construct(
        private PostalCodeHandler $primaryHandler,
        private PostalCodeHandler $fallbackHandler,
        private callable $condition
    ) {}

    public function validate(string $postalCode): bool
    {
        $handler = ($this->condition)($postalCode)
            ? $this->primaryHandler
            : $this->fallbackHandler;

        return $handler->validate($postalCode);
    }

    // ... other methods
}

// Usage: Different rules for different regions
PostalCode::extend('XX', new ConditionalHandler(
    primaryHandler: new RegionAHandler(),
    fallbackHandler: new RegionBHandler(),
    condition: fn($code) => str_starts_with($code, 'A')
));
```

## Handler Factory

```php
use Cline\PostalCode\PostalCode;

class PostalCodeHandlerFactory
{
    public static function make(string $country): PostalCodeHandler
    {
        return match($country) {
            'US' => new USPostalCodeHandler(),
            'GB' => new UKPostalCodeHandler(),
            'DE' => new GermanPostalCodeHandler(),
            default => new GenericPostalCodeHandler(),
        };
    }
}
```

<a id="doc-docs-exception-handling"></a>

Handle postal code validation errors gracefully.

## Validation Exceptions

```php
use Cline\PostalCode\PostalCode;
use Cline\PostalCode\Exceptions\InvalidPostalCodeException;

try {
    $result = PostalCode::validateOrFail('invalid', 'US');
} catch (InvalidPostalCodeException $e) {
    echo $e->getMessage();
    // "The postal code 'invalid' is not valid for US"

    echo $e->getPostalCode(); // "invalid"
    echo $e->getCountry(); // "US"
}
```

## Country Exceptions

```php
use Cline\PostalCode\Exceptions\UnsupportedCountryException;

try {
    PostalCode::validate('12345', 'XX');
} catch (UnsupportedCountryException $e) {
    echo $e->getMessage();
    // "The country 'XX' is not supported"

    echo $e->getCountry(); // "XX"
}
```

## Safe Validation

```php
// Returns result object instead of throwing
$result = PostalCode::validate('invalid', 'US');

if (!$result->isValid()) {
    $error = $result->error();
    // Handle error without exception
}

// Boolean check (never throws)
$isValid = PostalCode::isValid('90210', 'US');
```

## Custom Error Messages

```php
use Cline\PostalCode\PostalCode;
use Cline\PostalCode\Exceptions\InvalidPostalCodeException;

InvalidPostalCodeException::$messageFormat =
    'ZIP code ":code" is invalid for :country';

try {
    PostalCode::validateOrFail('bad', 'US');
} catch (InvalidPostalCodeException $e) {
    echo $e->getMessage();
    // "ZIP code 'bad' is invalid for US"
}
```

## Batch Validation with Error Collection

```php
use Cline\PostalCode\PostalCode;

$codes = ['90210', 'invalid', '10001', 'bad'];
$errors = [];
$valid = [];

foreach ($codes as $code) {
    $result = PostalCode::validate($code, 'US');

    if ($result->isValid()) {
        $valid[] = $result->formatted();
    } else {
        $errors[] = [
            'code' => $code,
            'error' => $result->error(),
        ];
    }
}

// $valid = ['90210', '10001']
// $errors = [
//     ['code' => 'invalid', 'error' => '...'],
//     ['code' => 'bad', 'error' => '...'],
// ]
```

## Laravel Validation

```php
use Cline\PostalCode\Rules\PostalCodeRule;

// In form request
public function rules(): array
{
    return [
        'postal_code' => ['required', new PostalCodeRule('US')],
    ];
}

// Dynamic country from request
public function rules(): array
{
    return [
        'country' => ['required', 'string', 'size:2'],
        'postal_code' => ['required', new PostalCodeRule($this->country)],
    ];
}

// Custom error message
public function messages(): array
{
    return [
        'postal_code' => 'Please enter a valid ZIP code.',
    ];
}
```

## Error Logging

```php
use Cline\PostalCode\PostalCode;
use Cline\PostalCode\Exceptions\InvalidPostalCodeException;
use Illuminate\Support\Facades\Log;

function validatePostalCode(string $code, string $country): ?string
{
    try {
        $result = PostalCode::validateOrFail($code, $country);
        return $result->formatted();
    } catch (InvalidPostalCodeException $e) {
        Log::warning('Invalid postal code submitted', [
            'code' => $code,
            'country' => $country,
            'error' => $e->getMessage(),
            'user_id' => auth()->id(),
        ]);
        return null;
    }
}
```

## Exception Hierarchy

```php
// Base exception
Cline\PostalCode\Exceptions\PostalCodeException

// Specific exceptions
├── InvalidPostalCodeException  // Invalid format
├── UnsupportedCountryException // Country not supported
└── HandlerException            // Handler error
```

<a id="doc-docs-laravel-integration"></a>

Use Postal Code with Laravel applications.

## Installation

```bash
composer require cline/postal-code
```

The service provider is auto-discovered in Laravel.

## Validation Rules

### Basic Rule

```php
use Cline\PostalCode\Rules\PostalCodeRule;

// In form request
public function rules(): array
{
    return [
        'postal_code' => ['required', new PostalCodeRule('US')],
    ];
}
```

### Dynamic Country

```php
public function rules(): array
{
    return [
        'country' => ['required', 'string', 'size:2'],
        'postal_code' => [
            'required',
            new PostalCodeRule($this->input('country')),
        ],
    ];
}
```

### Custom Messages

```php
public function rules(): array
{
    return [
        'postal_code' => ['required', new PostalCodeRule('US')],
    ];
}

public function messages(): array
{
    return [
        'postal_code' => 'Please enter a valid ZIP code.',
    ];
}
```

## Validation Rule String

```php
// Using rule string (if registered)
public function rules(): array
{
    return [
        'postal_code' => 'required|postal_code:US',
    ];
}

// Register in AppServiceProvider
use Cline\PostalCode\PostalCode;
use Illuminate\Support\Facades\Validator;

public function boot(): void
{
    Validator::extend('postal_code', function ($attribute, $value, $parameters) {
        $country = $parameters[0] ?? 'US';
        return PostalCode::isValid($value, $country);
    });
}
```

## Facade

```php
use Cline\PostalCode\Facades\PostalCode;

// Validate
$result = PostalCode::validate('90210', 'US');

// Quick check
PostalCode::isValid('90210', 'US');

// Format
PostalCode::format('sw1a1aa', 'GB'); // "SW1A 1AA"
```

## Model Casting

```php
use Cline\PostalCode\Casts\PostalCodeCast;

class Address extends Model
{
    protected $casts = [
        'postal_code' => PostalCodeCast::class . ':US',
    ];
}

// Usage
$address = new Address();
$address->postal_code = '90210';
$address->postal_code; // Returns formatted postal code
```

## Eloquent Accessor

```php
use Cline\PostalCode\PostalCode;

class Address extends Model
{
    public function getFormattedPostalCodeAttribute(): string
    {
        $result = PostalCode::validate(
            $this->postal_code,
            $this->country_code
        );

        return $result->isValid()
            ? $result->formatted()
            : $this->postal_code;
    }
}
```

## Blade Directive

```php
// Register in AppServiceProvider
Blade::directive('postalcode', function ($expression) {
    return "<?php echo \Cline\PostalCode\PostalCode::format($expression); ?>";
});

// Usage in Blade
@postalcode($address->postal_code, $address->country)
```

## API Resource

```php
use Cline\PostalCode\PostalCode;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request): array
    {
        $result = PostalCode::validate(
            $this->postal_code,
            $this->country_code
        );

        return [
            'street' => $this->street,
            'city' => $this->city,
            'postal_code' => $result->formatted(),
            'postal_code_valid' => $result->isValid(),
            'country' => $this->country_code,
        ];
    }
}
```

## Configuration

```php
// config/postal-code.php
return [
    // Default country for validation
    'default_country' => env('POSTAL_CODE_COUNTRY', 'US'),

    // Custom handlers
    'handlers' => [
        // 'XX' => App\PostalCode\CustomHandler::class,
    ],
];
```

```bash
php artisan vendor:publish --tag=postal-code-config
```
