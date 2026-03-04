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
