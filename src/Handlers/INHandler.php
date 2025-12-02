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
 * Postal code handler for India (IN).
 *
 * India uses the PIN (Postal Index Number) system consisting of 6 digits
 * without separators. Postal codes cannot start with 0, as the first digit
 * represents one of the eight postal regions in India.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_Index_Number
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class INHandler implements PostalCodeHandler
{
    /**
     * Validates an Indian PIN code.
     *
     * Postal codes are valid if they consist of exactly 6 digits and
     * do not start with 0.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats an Indian PIN code to its standard representation.
     *
     * Returns the postal code without modifications if valid, or returns
     * the original input unchanged if invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the postal code format.
     *
     * @return string Description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 6 digits, without separator.';
    }

    /**
     * Validates and formats the postal code.
     *
     * Checks if the postal code matches the required 6-digit pattern
     * and rejects codes starting with 0, as the first digit indicates
     * the postal region and must be 1-8.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The validated postal code or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{6}$/', $postalCode) !== 1) {
            return null;
        }

        // Indian PIN codes cannot start with 0
        if ($postalCode[0] === '0') {
            return null;
        }

        return $postalCode;
    }
}
