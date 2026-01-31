<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;

use function in_array;
use function mb_substr;
use function preg_match;

/**
 * Validates and formats postal codes in Slovakia.
 *
 * Slovak postal codes consist of 5 digits formatted as XXX XX with a space separator
 * after the third digit. The first digit represents the postal district and must be
 * 0, 8, or 9. The remaining digits provide further geographical subdivision within
 * each district.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class SKHandler implements PostalCodeHandler
{
    /**
     * Validates a Slovak postal code.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   Returns true if the postal code is 5 digits starting with 0, 8, or 9, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a Slovak postal code with space separator.
     *
     * Inserts a space after the third digit to produce the standard XXX XX format.
     * Invalid codes are returned as-is.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code (XXX XX), or the original value if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the postal code format.
     *
     * @return string Description of valid Slovak postal code format
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 5 digits in the following format: xxx xx.';
    }

    /**
     * Performs validation and formatting of Slovak postal codes.
     *
     * Validates that the postal code consists of exactly 5 digits and that the first
     * digit is a valid postal district identifier (0, 8, or 9). Formats valid codes
     * by inserting a space after the third digit.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The formatted postal code (XXX XX), or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        $district = $postalCode[0];

        if (!in_array($district, ['8', '9', '0'], true)) {
            return null;
        }

        return mb_substr($postalCode, 0, 3).' '.mb_substr($postalCode, 3);
    }
}
