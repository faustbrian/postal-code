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
 * Validates and formats postal codes for Palau.
 *
 * Palau uses the US ZIP code system with all locations assigned the single
 * ZIP code 96940. Supports both 5-digit ZIP codes and 9-digit ZIP+4 codes,
 * which are formatted with a hyphen separator (e.g., 96940-1234).
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class PWHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code is valid for Palau.
     *
     * @param  string $postalCode The postal code to validate (5 or 9 digits)
     * @return bool   Returns true if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard format for Palau.
     *
     * Normalizes valid ZIP codes by adding the hyphen separator for ZIP+4
     * format (e.g., "969401234" becomes "96940-1234"). 5-digit ZIP codes
     * are returned as-is. Invalid postal codes are returned unchanged.
     *
     * @param  string $postalCode The postal code to format (5 or 9 digits)
     * @return string The formatted postal code or the original if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about postal code requirements.
     *
     * @return string A description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'U.S. ZIP codes. All locations 96940.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code contains only digits, has the correct length
     * (5 or 9 digits), and matches Palau's single allocated ZIP code (96940).
     * Formats ZIP+4 codes with a hyphen separator.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Ensure postal code contains only digits
        if (preg_match('/^\d+$/', $postalCode) !== 1) {
            return null;
        }

        $length = mb_strlen($postalCode);

        // Extract the 5-digit ZIP code portion
        if ($length === 5) {
            $zip = $postalCode;
        } elseif ($length === 9) {
            $zip = mb_substr($postalCode, 0, 5);
        } else {
            return null;
        }

        // Verify ZIP code matches Palau's single allocated code
        if ($zip !== '96940') {
            return null;
        }

        // Return 5-digit ZIP as-is, or format ZIP+4 with hyphen
        if ($length === 5) {
            return $postalCode;
        }

        return $zip.'-'.mb_substr($postalCode, 5);
    }
}
