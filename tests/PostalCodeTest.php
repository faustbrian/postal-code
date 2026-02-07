<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Exceptions\InvalidPostalCodeException;
use Cline\PostalCode\Exceptions\UnknownCountryException;
use Cline\PostalCode\Facades\PostalCode;

describe('PostalCode Fluent API', function (): void {
    test('validates postalCodes via facade', function (): void {
        expect(PostalCode::validate('WC2E9RZ', 'GB'))->toBeTrue();
        expect(PostalCode::validate('INVALID', 'GB'))->toBeFalse();
    });

    test('formats postalCodes via facade', function (): void {
        expect(PostalCode::format('WC2E9RZ', 'GB'))->toBe('WC2E 9RZ');
    });

    test('provides fluent interface via for()', function (): void {
        $postalCode = PostalCode::for('WC2E9RZ', 'GB');

        expect($postalCode->isValid())->toBeTrue();
        expect($postalCode->format())->toBe('WC2E 9RZ');
        expect($postalCode->country())->toBe('GB');
        expect($postalCode->original())->toBe('WC2E9RZ');
        expect($postalCode->normalized())->toBe('WC2E9RZ');
    });

    test('handles invalid postalCodes in fluent interface', function (): void {
        $postalCode = PostalCode::for('INVALID', 'GB');

        expect($postalCode->isValid())->toBeFalse();
        expect($postalCode->formatOrNull())->toBeNull();
        expect($postalCode->formatOr('N/A'))->toBe('N/A');
    });

    test('throws exception when formatting invalid postalCode', function (): void {
        $postalCode = PostalCode::for('INVALID', 'GB');

        $postalCode->format();
    })->throws(InvalidPostalCodeException::class);

    test('returns hint for postalCode format', function (): void {
        $postalCode = PostalCode::for('1234', 'AF');

        expect($postalCode->hint())->toBeString()->not->toBeEmpty();
    });

    test('checks if country is supported', function (): void {
        expect(PostalCode::for('', 'GB')->isCountrySupported())->toBeTrue();
        expect(PostalCode::for('', 'XX')->isCountrySupported())->toBeFalse();
    });

    test('throws UnknownCountryException for isValid on unknown country', function (): void {
        $postalCode = PostalCode::for('12345', 'XX');
        $exception = null;

        try {
            $postalCode->isValid();
        } catch (UnknownCountryException $unknownCountryException) {
            $exception = $unknownCountryException;
        }

        expect($exception)->toBeInstanceOf(UnknownCountryException::class);
        expect($exception?->getCountry())->toBe('XX');
    });

    test('throws UnknownCountryException for formatOrNull on unknown country', function (): void {
        $postalCode = PostalCode::for('12345', 'XX');
        $exception = null;

        try {
            $postalCode->formatOrNull();
        } catch (UnknownCountryException $unknownCountryException) {
            $exception = $unknownCountryException;
        }

        expect($exception)->toBeInstanceOf(UnknownCountryException::class);
        expect($exception?->getCountry())->toBe('XX');
    });

    test('supports facade mocking through shouldReceive', function (): void {
        PostalCode::shouldReceive('format')
            ->once()
            ->with('12345', 'XX')
            ->andThrow(UnknownCountryException::forCountry('XX'));

        expect(static fn (): string => PostalCode::format('12345', 'XX'))
            ->toThrow(UnknownCountryException::class);
    });

    test('converts to string', function (): void {
        expect((string) PostalCode::for('WC2E9RZ', 'GB'))->toBe('WC2E 9RZ');
        expect((string) PostalCode::for('INVALID', 'GB'))->toBe('INVALID');
    });
});
