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
 * Postal code handler for Israel (IL).
 *
 * Israel uses a 7-digit postal code system without separators, introduced
 * in 2013 to replace the previous 5-digit system. Only the current 7-digit
 * format is supported by this handler.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Israel
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class ILHandler implements PostalCodeHandler
{
    /**
     * Validates an Israeli postal code.
     *
     * Postal codes are valid if they consist of exactly 7 digits.
     * Legacy 5-digit postal codes from before 2013 are not supported.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats an Israeli postal code to its standard representation.
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
        return 'PostalCodes consist of 7 digits, without separator.';
    }

    /**
     * Validates and formats the postal code.
     *
     * Checks if the postal code matches the required 7-digit pattern.
     * Returns the postal code unchanged if valid, or null if invalid.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The validated postal code or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{7}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
