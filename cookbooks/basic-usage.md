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
