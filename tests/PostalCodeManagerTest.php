<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Contracts\PostalCodeHandler;
use Cline\PostalCode\Exceptions\InvalidPostalCodeException;
use Cline\PostalCode\Exceptions\UnknownCountryException;
use Cline\PostalCode\PostalCodeManager;

describe('PostalCodeManager', function (): void {
    test('throws UnknownCountryException for unknown country', function (): void {
        $manager = new PostalCodeManager();

        $manager->format('12345', 'XX');
    })->throws(UnknownCountryException::class);

    test('throws InvalidPostalCodeException for invalid postalCode', function (string $postalCode, string $country): void {
        $manager = new PostalCodeManager();

        $manager->format($postalCode, $country);
    })->with([
        ['', 'FR'],
        ['123456', 'FR'],
        ['ABCDEFG', 'GB'],
        ['12*345', 'PL'],
    ])->throws(InvalidPostalCodeException::class);

    test('formats valid postalCodes', function (string $postalCode, string $country, string $expected): void {
        $manager = new PostalCodeManager();

        expect($manager->format($postalCode, $country))->toBe($expected);
    })->with([
        ['WC2E9RZ', 'GB', 'WC2E 9RZ'],
        ['wc-2E9RZ', 'gb', 'WC2E 9RZ'],
        ['12345', 'PL', '12-345'],
    ]);

    test('validates postalCodes', function (string $postalCode, string $country, bool $expected): void {
        $manager = new PostalCodeManager();

        expect($manager->validate($postalCode, $country))->toBe($expected);
    })->with([
        ['WC2E9RZ', 'GB', true],
        ['INVALID', 'GB', false],
        ['12345', 'PL', true],
        ['1234', 'PL', false],
    ]);

    test('normalizes spaces hyphens and casing before validation and formatting', function (
        string $input,
        string $country,
        string $expectedFormat,
    ): void {
        $manager = new PostalCodeManager();

        expect($manager->validate($input, $country))->toBeTrue();
        expect($manager->format($input, $country))->toBe($expectedFormat);
    })->with([
        ['wc-2e 9rz', 'gb', 'WC2E 9RZ'],
        ['12 345', 'pl', '12-345'],
        ['12-345', 'FI', '12345'],
        ['12 345-6789', 'us', '12345-6789'],
    ]);

    test('rejects unsupported punctuation characters during validation', function (
        string $input,
        string $country,
    ): void {
        $manager = new PostalCodeManager();

        expect($manager->validate($input, $country))->toBeFalse();
        expect($manager->formatOrNull($input, $country))->toBeNull();
    })->with([
        ['12_345', 'PL'],
        ['WC2E.9RZ', 'GB'],
        ['12345/6789', 'US'],
    ]);

    test('throws UnknownCountryException for validate on unknown country', function (): void {
        $manager = new PostalCodeManager();
        $exception = null;

        try {
            $manager->validate('12345', 'XX');
        } catch (UnknownCountryException $unknownCountryException) {
            $exception = $unknownCountryException;
        }

        expect($exception)->toBeInstanceOf(UnknownCountryException::class);
        expect($exception?->getCountry())->toBe('XX');
    });

    test('checks if country is supported', function (string $country, bool $expected): void {
        $manager = new PostalCodeManager();

        expect($manager->isSupportedCountry($country))->toBe($expected);
    })->with([
        ['fr', true],
        ['GB', true],
        ['XX', false],
        ['UnknownCountry', false],
    ]);

    test('returns formatted postalCode or null for invalid', function (): void {
        $manager = new PostalCodeManager();

        expect($manager->formatOrNull('WC2E9RZ', 'GB'))->toBe('WC2E 9RZ');
        expect($manager->formatOrNull('INVALID', 'GB'))->toBeNull();
    });

    test('throws UnknownCountryException for formatOrNull on unknown country', function (): void {
        $manager = new PostalCodeManager();
        $exception = null;

        try {
            $manager->formatOrNull('12345', 'XX');
        } catch (UnknownCountryException $unknownCountryException) {
            $exception = $unknownCountryException;
        }

        expect($exception)->toBeInstanceOf(UnknownCountryException::class);
        expect($exception?->getCountry())->toBe('XX');
    });

    test('returns hint for country', function (): void {
        $manager = new PostalCodeManager();

        expect($manager->getHint('AF'))->toBeString()->not->toBeEmpty();
    });

    test('throws UnknownCountryException for getHint on unknown country', function (): void {
        $manager = new PostalCodeManager();
        $exception = null;

        try {
            $manager->getHint('XX');
        } catch (UnknownCountryException $unknownCountryException) {
            $exception = $unknownCountryException;
        }

        expect($exception)->toBeInstanceOf(UnknownCountryException::class);
        expect($exception?->getCountry())->toBe('XX');
        expect($exception?->getMessage())->toContain('Unknown country: XX');
    });

    test('allows registering custom handlers', function (): void {
        $manager = new PostalCodeManager();

        $customHandler = new class() implements PostalCodeHandler
        {
            public function validate(string $postalCode): bool
            {
                return $postalCode === 'CUSTOM123';
            }

            public function format(string $postalCode): string
            {
                return 'CUSTOM-123';
            }

            public function hint(): string
            {
                return 'Custom format';
            }
        };

        $manager->registerHandler('ZZ', $customHandler::class);

        expect($manager->isSupportedCountry('ZZ'))->toBeTrue();
        expect($manager->validate('CUSTOM123', 'ZZ'))->toBeTrue();
        expect($manager->validate('OTHER', 'ZZ'))->toBeFalse();
        expect($manager->format('CUSTOM123', 'ZZ'))->toBe('CUSTOM-123');
        expect($manager->getHint('ZZ'))->toBe('Custom format');
    });

    test('custom handlers override default handlers', function (): void {
        $customHandler = new class() implements PostalCodeHandler
        {
            public function validate(string $postalCode): bool
            {
                return $postalCode === '99999';
            }

            public function format(string $postalCode): string
            {
                return '99-999';
            }

            public function hint(): string
            {
                return 'Custom DE format';
            }
        };

        $manager = new PostalCodeManager([
            'DE' => $customHandler::class,
        ]);

        expect($manager->validate('99999', 'DE'))->toBeTrue();
        expect($manager->validate('12345', 'DE'))->toBeFalse();
    });

    test('replacing an already cached custom handler takes effect immediately', function (): void {
        $firstHandler = new class() implements PostalCodeHandler
        {
            public function validate(string $postalCode): bool
            {
                return $postalCode === 'VALUE';
            }

            public function format(string $postalCode): string
            {
                return 'FIRST';
            }

            public function hint(): string
            {
                return 'First format';
            }
        };

        $secondHandler = new class() implements PostalCodeHandler
        {
            public function validate(string $postalCode): bool
            {
                return $postalCode === 'VALUE';
            }

            public function format(string $postalCode): string
            {
                return 'SECOND';
            }

            public function hint(): string
            {
                return 'Second format';
            }
        };

        $manager = new PostalCodeManager();
        $manager->registerHandler('ZZ', $firstHandler::class);

        expect($manager->format('value', 'ZZ'))->toBe('FIRST');

        $manager->registerHandler('ZZ', $secondHandler::class);

        expect($manager->format('value', 'ZZ'))->toBe('SECOND');
    });

    test('treats missing custom handler class as unknown country', function (): void {
        $manager = new PostalCodeManager([
            'ZZ' => 'App\\Missing\\Handler',
        ]);

        expect($manager->isSupportedCountry('ZZ'))->toBeFalse();

        $exception = null;

        try {
            $manager->format('12345', 'ZZ');
        } catch (UnknownCountryException $unknownCountryException) {
            $exception = $unknownCountryException;
        }

        expect($exception)->toBeInstanceOf(UnknownCountryException::class);
        expect($exception?->getCountry())->toBe('ZZ');
    });

    test('invalid postalCode exception includes country postalCode and hint', function (): void {
        $manager = new PostalCodeManager();
        $exception = null;

        try {
            $manager->format('12*345', 'PL');
        } catch (InvalidPostalCodeException $invalidPostalCodeException) {
            $exception = $invalidPostalCodeException;
        }

        expect($exception)->toBeInstanceOf(InvalidPostalCodeException::class);
        expect($exception?->getPostalCode())->toBe('12*345');
        expect($exception?->getCountry())->toBe('PL');
        expect($exception?->getHint())->toBeString()->not->toBeEmpty();
    });
});
