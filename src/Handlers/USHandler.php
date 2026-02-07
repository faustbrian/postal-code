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
 * Validates and formats postal codes for the United States of America.
 *
 * The United States uses ZIP codes (Zone Improvement Plan) for mail routing.
 * Two formats are supported:
 * - Standard ZIP: 5 digits identifying a delivery area
 * - ZIP+4: 5 digits, hyphen, then 4 additional digits for more precise delivery
 *
 * The additional 4 digits in ZIP+4 codes can identify specific delivery segments
 * such as city blocks, building floors, or individual high-volume mail recipients.
 *
 * Accepted formats:
 * - NNNNN (5 digits): Standard ZIP code
 * - NNNNNNNNN (9 digits): Full ZIP+4, formatted as NNNNN-NNNN
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class USHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches US ZIP code format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   Returns true if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to US ZIP code standards.
     *
     * 5-digit ZIP codes are returned as-is. 9-digit ZIP+4 codes are formatted
     * with a hyphen separator as NNNNN-NNNN. Invalid codes are returned unchanged.
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
     * @return string Format hint for US ZIP codes
     */
    public function hint(): string
    {
        return 'PostalCodes in the USA are called ZIP codes.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the input contains only digits and has a length of either 5 or 9.
     * For 9-digit ZIP+4 codes, inserts a hyphen separator between the fifth and sixth digits.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        // ZIP codes must contain only digits
        if (preg_match('/^\d+$/', $postalCode) !== 1) {
            return null;
        }

        $length = mb_strlen($postalCode);

        // Accept standard 5-digit ZIP codes
        if ($length === 5) {
            return $postalCode;
        }

        // Format 9-digit ZIP+4 codes with hyphen separator
        if ($length === 9) {
            return mb_substr($postalCode, 0, 5).'-'.mb_substr($postalCode, -4);
        }

        return null;
    }
}
