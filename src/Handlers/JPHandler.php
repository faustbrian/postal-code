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

/**
 * Validates and formats postal codes for Japan (JP).
 *
 * Japanese postal codes consist of 7 digits formatted as NNN-NNNN, where N
 * represents a digit. The first three digits represent the area code, while
 * the last four digits specify the local delivery area. The current 7-digit
 * system was introduced in 1998, replacing the earlier 5-digit system.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Japan
 */
final class JPHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Japanese format.
     *
     * Checks if the postal code consists of exactly 7 consecutive digits
     * without any separator or other characters.
     *
     * @param  string $postalCode The postal code string to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Japanese standards.
     *
     * Transforms a 7-digit postal code into the standard Japanese format
     * with a hyphen separator between the area code and local code
     * (NNN-NNNN). If the postal code is invalid, returns it unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or the original if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the expected postal code format.
     *
     * @return string Description of the Japanese postal code format
     */
    public function hint(): string
    {
        return 'PostalCodes format is NNN-NNNN, where N stands for a digit.';
    }

    /**
     * Validates and formats the postal code in a single operation.
     *
     * This internal method verifies the postal code consists of exactly
     * 7 digits, then splits it into the area code (first 3 digits) and
     * local code (last 4 digits) separated by a hyphen. Uses multibyte
     * string functions to ensure proper handling of character encodings.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code, or null if validation fails
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{7}$/', $postalCode) !== 1) {
            return null;
        }

        return mb_substr($postalCode, 0, 3).'-'.mb_substr($postalCode, 3);
    }
}
