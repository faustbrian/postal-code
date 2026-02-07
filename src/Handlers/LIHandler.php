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
 * Validates and formats postal codes for Liechtenstein (LI).
 *
 * Liechtenstein uses a 4-digit numeric postal code system ranging from
 * 9485 to 9498. This limited range reflects the small geographic area
 * of the principality. Codes require no formatting as they are always
 * 4 consecutive digits.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Switzerland_and_Liechtenstein
 */
final class LIHandler implements PostalCodeHandler
{
    /**
     * Validates a postal code for Liechtenstein.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code for Liechtenstein.
     *
     * Returns the postal code unchanged if valid, or the original
     * input if invalid. Liechtenstein postal codes require no formatting
     * as they are always 4 consecutive digits.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or original input if invalid
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
        return 'PostalCodes consist of 4 digits, without separator, range 9485 to 9498.';
    }

    /**
     * Validates and formats the postal code internally.
     *
     * Performs two-step validation:
     * 1. Verifies the input matches the 4-digit pattern
     * 2. Checks that the code falls within the valid range (9485-9498)
     *
     * Uses lexicographic comparison which works correctly for 4-digit
     * strings as all valid codes start with '9'.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Must have exactly 4 digits
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        // Must be within valid range for Liechtenstein
        if ($postalCode < '9485' || $postalCode > '9498') {
            return null;
        }

        return $postalCode;
    }
}
