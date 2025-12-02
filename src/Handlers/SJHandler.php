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
 * Validates and formats postal codes in Svalbard and Jan Mayen.
 *
 * These Norwegian territories use the Norwegian postal code system, consisting
 * of exactly 4 digits with no separator characters. Svalbard uses codes in the
 * 9170-9179 range, while Jan Mayen uses 8099.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Norway
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class SJHandler implements PostalCodeHandler
{
    /**
     * Validates a postal code for Svalbard and Jan Mayen.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   Returns true if the postal code consists of exactly 4 digits, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code for Svalbard and Jan Mayen.
     *
     * Since Norwegian postal codes have no separator, this returns the code unchanged
     * if valid, or the original input if invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The postal code unchanged if valid, or the original value if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the postal code format.
     *
     * @return string Description of valid Norwegian postal code format
     */
    public function hint(): string
    {
        return 'This country uses Norwegian 4-digit postal codes.';
    }

    /**
     * Performs validation of Norwegian postal codes.
     *
     * Validates that the postal code matches exactly 4 digits with no other characters.
     * Note: This validator does not restrict to the specific ranges used by Svalbard
     * and Jan Mayen, accepting any valid Norwegian 4-digit postal code.
     *
     * @param  string      $postalCode The postal code to validate
     * @return null|string The postal code if valid, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
