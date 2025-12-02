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
 * Validates and formats postal codes for Mayotte.
 *
 * Handles French postal codes used in this Overseas Department of France,
 * consisting of exactly 5 numeric digits in the range 97600-97690.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class YTHandler implements PostalCodeHandler
{
    /**
     * Validates whether the given postal code is valid for Mayotte.
     *
     * Postal codes must be exactly 5 numeric digits within the valid range
     * of 97600-97690 assigned to this territory.
     *
     * @param  string $postalCode The postal code to validate (without spaces or hyphens)
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Mayotte standards.
     *
     * Since these postal codes have no special formatting, this returns the
     * validated postal code as-is, or the original input if invalid.
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
        return 'PostalCodes consist of 5 digits, without separator.';
    }

    /**
     * Internal method that validates and formats the postal code.
     *
     * Ensures the postal code consists of exactly 5 digits and falls within
     * the valid range for Mayotte (97600-97690).
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The validated postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        // Validate postal code is within the valid range for Mayotte
        if ($postalCode < '97600' || $postalCode > '97690') {
            return null;
        }

        return $postalCode;
    }
}
