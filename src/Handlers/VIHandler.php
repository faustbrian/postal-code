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
 * Validates and formats postal codes for the U.S. Virgin Islands.
 *
 * Handles U.S. ZIP codes in the range 00801-00851, supporting both 5-digit
 * and 9-digit (ZIP+4) formats. Automatically formats 9-digit codes with
 * the standard hyphen separator (XXXXX-XXXX).
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class VIHandler implements PostalCodeHandler
{
    /**
     * Validates whether the given postal code is valid for the U.S. Virgin Islands.
     *
     * Accepts both 5-digit ZIP codes and 9-digit ZIP+4 codes within the valid
     * range of 00801-00851.
     *
     * @param  string $postalCode The postal code to validate (without spaces or hyphens)
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to U.S. Virgin Islands standards.
     *
     * Returns the properly formatted postal code with ZIP+4 codes formatted as
     * XXXXX-XXXX. If the postal code is invalid, returns the original input.
     *
     * @param  string $postalCode The postal code to format (without spaces or hyphens)
     * @return string The formatted postal code, or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the expected postal code format.
     *
     * @return string Description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'U.S. ZIP codes. Range 00801 - 00851.';
    }

    /**
     * Internal method that validates and formats the postal code.
     *
     * Performs the core validation logic, ensuring the postal code contains
     * only digits, has the correct length (5 or 9 digits), and falls within
     * the valid range for U.S. Virgin Islands ZIP codes (00801-00851).
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code, or null if invalid
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

        // Validate ZIP code is within the valid range for U.S. Virgin Islands
        if ($zip < '00801' || $zip > '00851') {
            return null;
        }

        if ($length === 5) {
            return $postalCode;
        }

        // Format ZIP+4 code with hyphen separator
        return $zip.'-'.mb_substr($postalCode, 5);
    }
}
