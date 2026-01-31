<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Support\Country;

describe('Country', function (): void {
    describe('hasPostalCode', function (): void {
        test('returns true for countries with postal codes', function (string $country): void {
            expect(Country::hasPostalCode($country))->toBeTrue();
        })->with([
            'US',
            'GB',
            'DE',
            'FI',
            'SE',
            'FR',
            'CA',
            'AU',
            'JP',
            'NL',
        ]);

        test('returns false for countries without postal codes', function (string $country): void {
            expect(Country::hasPostalCode($country))->toBeFalse();
        })->with([
            'AE', // United Arab Emirates
            'HK', // Hong Kong
            'QA', // Qatar
            'AG', // Antigua and Barbuda
            'BS', // Bahamas
            'BW', // Botswana
            'GH', // Ghana
            'JM', // Jamaica
            'MO', // Macau
            'UG', // Uganda
        ]);

        test('handles lowercase country codes', function (): void {
            expect(Country::hasPostalCode('hk'))->toBeFalse();
            expect(Country::hasPostalCode('us'))->toBeTrue();
        });

        test('handles mixed case country codes', function (): void {
            expect(Country::hasPostalCode('Hk'))->toBeFalse();
            expect(Country::hasPostalCode('Us'))->toBeTrue();
        });
    });

    describe('fallbackPostalCode', function (): void {
        test('returns 00000', function (): void {
            expect(Country::fallbackPostalCode())->toBe('00000');
        });

        test('returns a 5-character string', function (): void {
            expect(Country::fallbackPostalCode())->toHaveLength(5);
        });
    });

    describe('countriesWithoutPostalCodes', function (): void {
        test('returns an array', function (): void {
            expect(Country::countriesWithoutPostalCodes())->toBeArray();
        });

        test('contains only uppercase 2-letter codes', function (): void {
            foreach (Country::countriesWithoutPostalCodes() as $code) {
                expect($code)->toMatch('/^[A-Z]{2}$/');
            }
        });

        test('contains known countries without postal codes', function (): void {
            $countries = Country::countriesWithoutPostalCodes();

            expect($countries)->toContain('AE');
            expect($countries)->toContain('HK');
            expect($countries)->toContain('QA');
        });

        test('does not contain countries with postal codes', function (): void {
            $countries = Country::countriesWithoutPostalCodes();

            expect($countries)->not->toContain('US');
            expect($countries)->not->toContain('GB');
            expect($countries)->not->toContain('DE');
        });
    });
});
