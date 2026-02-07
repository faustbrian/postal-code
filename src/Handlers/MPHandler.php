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
 * Validates and formats postal codes for Northern Mariana Islands (MP).
 *
 * Northern Mariana Islands uses U.S. ZIP code format with a restricted
 * range of 96950 to 96952. This handler accepts both 5-digit ZIP codes
 * and 9-digit ZIP+4 codes, formatting the latter with a hyphen separator.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_the_Northern_Mariana_Islands
 */
final class MPHandler implements PostalCodeHandler
{
    /**
     * Validates whether a postal code matches the Northern Mariana Islands format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code to the standard Northern Mariana Islands format.
     *
     * Returns the formatted postal code if valid, otherwise returns
     * the original input unchanged. ZIP+4 codes are formatted with
     * a hyphen separator (e.g., 96950-1234).
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the postal code format.
     *
     * @return string The format description for end users
     */
    public function hint(): string
    {
        return 'U.S. ZIP codes. Range 96950 - 96952.';
    }

    /**
     * Validates and formats the postal code according to Northern Mariana Islands rules.
     *
     * Accepts 5-digit or 9-digit numeric codes. Validates that the base ZIP code
     * falls within the valid range (96950-96952). Formats 9-digit codes with a
     * hyphen separator between the ZIP and +4 extension.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Must be all digits
        if (preg_match('/^\d+$/', $postalCode) !== 1) {
            return null;
        }

        $length = mb_strlen($postalCode);

        // Extract base ZIP code (first 5 digits)
        if ($length === 5) {
            $zip = $postalCode;
        } elseif ($length === 9) {
            $zip = mb_substr($postalCode, 0, 5);
        } else {
            return null;
        }

        // Validate ZIP code is within the valid range for Northern Mariana Islands
        if ($zip < '96950' || $zip > '96952') {
            return null;
        }

        // Return 5-digit ZIP as-is, or format 9-digit ZIP+4 with hyphen
        if ($length === 5) {
            return $postalCode;
        }

        return $zip.'-'.mb_substr($postalCode, 5);
    }
}
