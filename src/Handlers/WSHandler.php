<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;

use function mb_substr;
use function preg_match;
use function str_starts_with;

/**
 * Validates and formats postal codes for Samoa.
 *
 * Handles Samoan postal codes in the format WSNNNN, where WS is the country
 * prefix and NNNN represents 4 numeric digits. Accepts input with or without
 * the WS prefix, but always outputs the complete format with the prefix.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class WSHandler implements PostalCodeHandler
{
    /**
     * Validates whether the given postal code is valid for Samoa.
     *
     * Accepts postal codes with or without the WS prefix. The numeric portion
     * must be exactly 4 digits.
     *
     * @param  string $postalCode The postal code to validate (without spaces or hyphens)
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Samoan standards.
     *
     * Returns the postal code in the standard WSNNNN format, adding the WS prefix
     * if not already present. Returns the original input if invalid.
     *
     * @param  string $postalCode The postal code to format (without spaces or hyphens)
     * @return string The formatted postal code with WS prefix, or the original input if invalid
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
        return 'The postalCode format is WSNNNN, where N represents a digit.';
    }

    /**
     * Internal method that validates and formats the postal code.
     *
     * Strips the WS prefix if present, validates that the remaining portion
     * consists of exactly 4 digits, and returns the properly formatted code
     * with the WS prefix.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code with WS prefix, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Remove WS prefix if present for validation
        if (str_starts_with($postalCode, 'WS')) {
            $postalCode = mb_substr($postalCode, 2);
        }

        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        // Always return with WS prefix
        return 'WS'.$postalCode;
    }
}
