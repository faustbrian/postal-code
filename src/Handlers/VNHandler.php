<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;

use function preg_match;

/**
 * Validates and formats postal codes for Vietnam.
 *
 * Handles Vietnamese postal codes which consist of exactly 6 numeric digits
 * without any separators or formatting.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Vietnam
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class VNHandler implements PostalCodeHandler
{
    /**
     * Validates whether the given postal code is valid for Vietnam.
     *
     * Vietnamese postal codes must be exactly 6 numeric digits with no formatting.
     *
     * @param  string $postalCode The postal code to validate (without spaces or hyphens)
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Vietnamese standards.
     *
     * Since Vietnamese postal codes have no special formatting, this returns
     * the validated postal code as-is, or the original input if invalid.
     *
     * @param  string $postalCode The postal code to format (without spaces or hyphens)
     * @return string The formatted postal code, or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the expected postal code format.
     *
     * @return string Description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'Postal codes are 6 digit numeric.';
    }

    /**
     * Internal method that validates and formats the postal code.
     *
     * Ensures the postal code matches the Vietnamese format of exactly 6 numeric digits.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The validated postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{6}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
