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
 * Validates and formats postal codes for the British Virgin Islands.
 *
 * The British Virgin Islands uses postal codes in the format "VGNNNN", where
 * "VG" is the country prefix and "NNNN" represents four digits. Valid codes
 * are restricted to the range VG1110 through VG1160, representing specific
 * delivery zones within the territory.
 *
 * This handler accepts both formats with and without the "VG" prefix,
 * and always outputs the complete format with the prefix.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class VGHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches British Virgin Islands format.
     *
     * @param  string $postalCode The postal code to validate (with or without VG prefix)
     * @return bool   Returns true if the postal code is in the valid range VG1110-VG1160, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to British Virgin Islands standard format.
     *
     * Accepts postal codes with or without the "VG" prefix. Always outputs the
     * complete format with the "VG" prefix followed by 4 digits. The code must
     * fall within the valid range of 1110-1160. Invalid codes are returned unchanged.
     *
     * @param  string $postalCode The postal code to format (with or without VG prefix)
     * @return string The formatted postal code with VG prefix, or the original value if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable description of the accepted postal code format.
     *
     * @return string Format hint for British Virgin Islands postal codes
     */
    public function hint(): string
    {
        return 'The postalCode format is VG followed by 4 digits, specifically VG1110 through VG1160.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Strips the "VG" prefix if present, validates that the remaining part consists
     * of exactly 4 digits within the range 1110-1160, then returns the formatted
     * code with the "VG" prefix.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code with VG prefix if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        // Strip VG prefix if present to normalize input
        if (str_starts_with($postalCode, 'VG')) {
            $postalCode = mb_substr($postalCode, 2);
        }

        // Validate that the code consists of exactly 4 digits
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        // Validate that the code is within the valid range (1110-1160)
        if ($postalCode < '1110' || $postalCode > '1160') {
            return null;
        }

        // Return formatted code with VG prefix
        return 'VG'.$postalCode;
    }
}
