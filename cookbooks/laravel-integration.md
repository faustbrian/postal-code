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
