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
 * Handles postal code validation and formatting for Guam.
 *
 * Guam, as a U.S. territory, uses the U.S. ZIP code system with a specific range.
 * Valid ZIP codes for Guam fall within the range 96910 to 96932. Both 5-digit
 * and 9-digit (ZIP+4) formats are supported. The 9-digit format is formatted
 * with a hyphen separator (NNNNN-NNNN).
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class GUHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code conforms to Guam's format and range.
     *
     * @param  string $postalCode The postal code string to validate
     * @return bool   Returns true if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Guam's standard format.
     *
     * 5-digit ZIP codes are returned unchanged. 9-digit ZIP codes are formatted
     * with a hyphen separator (NNNNN-NNNN). Returns the original input if invalid.
     *
     * @param  string $postalCode The postal code string to format
     * @return string The formatted postal code, or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about the postal code format.
     *
     * @return string Description of the expected postal code format
     */
    public function hint(): string
    {
        return 'U.S. ZIP codes. Range 96910 - 96932.';
    }

    /**
     * Validates and formats the postal code using the Guam pattern and range.
     *
     * Validates that the input is numeric and either 5 or 9 digits in length.
     * Verifies that the base ZIP code (first 5 digits) falls within the valid
     * Guam range (96910-96932). Formats 9-digit ZIP codes with a hyphen separator.
     *
     * @param  string      $postalCode The postal code string to process
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
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

        // Validate ZIP code is within Guam's range
        if ($zip < '96910' || $zip > '96932') {
            return null;
        }

        if ($length === 5) {
            return $postalCode;
        }

        return $zip.'-'.mb_substr($postalCode, 5);
    }
}
