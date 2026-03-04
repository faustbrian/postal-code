<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;

use function mb_strlen;
use function mb_substr;
use function preg_match;

/**
 * Validates and formats postal codes for Taiwan.
 *
 * Taiwan postal codes consist of either 3 or 5 digits. The first three digits
 * identify the delivery district and are required. The last two digits are
 * optional and provide more precise location information within the district.
 *
 * Accepted formats:
 * - NNN (3 digits): Basic district code
 * - NNNNN (5 digits): Full postal code, formatted as NNN-NN
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes_in_Taiwan
 */
final class TWHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Taiwan's format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   Returns true if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Taiwan's standard format.
     *
     * 3-digit codes are returned as-is. 5-digit codes are formatted with a hyphen
     * separator as NNN-NN. Invalid codes are returned unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or the original value if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable description of the accepted postal code format.
     *
     * @return string Format hint for Taiwan postal codes
     */
    public function hint(): string
    {
        return 'Acceptable formats are NNN and NNN-NN, N standing for a digit.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the input contains only digits and has a length of either 3 or 5.
     * For 5-digit codes, inserts a hyphen separator between the third and fourth digits.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        // Postal codes must contain only digits
        if (preg_match('/^\d+$/', $postalCode) !== 1) {
            return null;
        }

        $length = mb_strlen($postalCode);

        // Accept 3-digit district codes
        if ($length === 3) {
            return $postalCode;
        }

        // Format 5-digit codes with hyphen separator
        if ($length === 5) {
            return mb_substr($postalCode, 0, 3).'-'.mb_substr($postalCode, -2);
        }

        return null;
    }
}
