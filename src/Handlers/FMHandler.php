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
 * Validates and formats postal codes for Micronesia.
 *
 * Micronesia uses U.S. ZIP code format with a restricted range from
 * 96941 to 96944. Postal codes can be provided in either 5-digit format
 * (NNNNN) or 9-digit ZIP+4 format (NNNNN-NNNN).
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class FMHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Micronesia's format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid for Micronesia, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Micronesia's standards.
     *
     * For 5-digit codes, returns the code as-is. For 9-digit ZIP+4 codes,
     * formats them with a hyphen separator (NNNNN-NNNN). Returns the
     * original input unchanged if invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable description of the postal code format.
     *
     * @return string Description of Micronesia's postal code requirements
     */
    public function hint(): string
    {
        return 'U.S. ZIP codes. Range 96941 - 96944.';
    }

    /**
     * Performs validation and formatting of the postal code.
     *
     * Validates that the code contains only digits and is either 5 or 9
     * characters long. For 5-digit codes, validates the range (96941-96944).
     * For 9-digit codes, extracts and validates the first 5 digits against
     * the allowed range, then formats with a hyphen separator.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The validated and formatted postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Postal code must contain only digits
        if (preg_match('/^\d+$/', $postalCode) !== 1) {
            return null;
        }

        $length = mb_strlen($postalCode);

        if ($length === 5) {
            $zip = $postalCode;
        } elseif ($length === 9) {
            $zip = mb_substr($postalCode, 0, 5);
        } else {
            return null;
        }

        // Validate ZIP code is within Micronesia's allowed range
        if ($zip < '96941' || $zip > '96944') {
            return null;
        }

        if ($length === 5) {
            return $postalCode;
        }

        // Format ZIP+4 with hyphen separator
        return $zip.'-'.mb_substr($postalCode, 5);
    }
}
