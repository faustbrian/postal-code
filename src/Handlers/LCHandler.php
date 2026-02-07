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
 * Validates and formats postal codes for Saint Lucia (LC).
 *
 * Saint Lucia uses the format LCNN NNN, where LC is the country prefix,
 * followed by two digits, a space, and three more digits. This handler
 * accepts input with or without spaces and formats it correctly.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class LCHandler implements PostalCodeHandler
{
    /**
     * Validates a postal code for Saint Lucia.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code for Saint Lucia.
     *
     * Accepts postal codes with or without spaces and returns them
     * in the standard LCNN NNN format. Returns the original input
     * if the postal code is invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code (LCNN NNN) or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the postal code format.
     *
     * @return string Description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'The postalCode format is LCNN NNN, N standing for a digit.';
    }

    /**
     * Validates and formats the postal code internally.
     *
     * Uses regex pattern matching to validate the LC prefix followed by
     * 5 digits, then formats the result with a space after the first
     * two digits (LCNN NNN format).
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code (LCNN NNN) or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Match LC prefix + 2 digits + 3 digits (captures two groups)
        if (preg_match('/^(LC\d{2})(\d{3})$/', $postalCode, $matches) !== 1) {
            return null;
        }

        // Format as LCNN NNN
        return $matches[1].' '.$matches[2];
    }
}
