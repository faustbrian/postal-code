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

    test('returns hint for country', function (): void {
        $manager = new PostalCodeManager();

        expect($manager->getHint('AF'))->toBeString()->not->toBeEmpty();
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
});
