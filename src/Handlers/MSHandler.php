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
 * Validates and formats postal codes for Montserrat (MS).
 *
 * Montserrat postal codes follow the format "MSR NNNN" where N represents
 * a digit in the range 1110 to 1350. This handler accepts postal codes
 * both with and without the "MSR" prefix, but always outputs the full
 * prefixed format.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class MSHandler implements PostalCodeHandler
{
    /**
     * Validates whether a postal code matches the Montserrat format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code to the standard Montserrat format.
     *
     * Returns the formatted postal code with the "MSR " prefix if valid,
     * otherwise returns the original input unchanged. Accepts input with
     * or without the prefix.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code (e.g., "MSR 1110"), or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the postal code format.
     *
     * @return string The format description for end users
     */
    public function hint(): string
    {
        return 'The format is MSR followed by a space then 4 digits, in the range 1110 to 1350.';
    }

    /**
     * Validates and formats the postal code according to Montserrat rules.
     *
     * Strips the "MSR" prefix if present, validates that the numeric portion
     * consists of exactly 4 digits in the range 1110-1350, and returns the
     * formatted postal code with the "MSR " prefix.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code with "MSR " prefix, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Strip "MSR" prefix if present to normalize input
        if (str_starts_with($postalCode, 'MSR')) {
            $postalCode = mb_substr($postalCode, 3);
        }

        // Validate numeric portion is exactly 4 digits
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        // Validate postal code is within the valid range for Montserrat
        if ($postalCode < '1110' || $postalCode > '1350') {
            return null;
        }

        return 'MSR '.$postalCode;
    }
}
