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
 * Validates and formats postal codes for Guernsey.
 *
 * Guernsey uses UK-style postcodes with the prefix "GY" (the Guernsey
 * postal area). Postcodes follow two possible formats: GY9 9AA or GY99 9AA,
 * where the outward code starts with GY followed by 1-2 digits, and the
 * inward code consists of a digit and two letters.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/GY_postcode_area
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class GGHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Guernsey's format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid for Guernsey, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Guernsey's standards.
     *
     * Converts unformatted postcodes to the standard format with a space
     * separating the outward and inward codes (e.g., "GY11AA" becomes
     * "GY1 1AA"). Returns the original input unchanged if invalid.
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
     * @return string Description of Guernsey's postal code requirements
     */
    public function hint(): string
    {
        return 'PostalCodes can have two different formats:';
    }

    /**
     * Performs validation and formatting of the postal code.
     *
     * Validates that the postcode starts with "GY" followed by 1-2 digits,
     * then a digit and two uppercase letters (NAA pattern for inward code).
     * Returns the formatted postcode with proper spacing between outward
     * and inward codes if valid.
     *
     * The pattern matches:
     * - GY9 9AA format (e.g., GY1 1AA)
     * - GY99 9AA format (e.g., GY10 1AA)
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^(GY\d{1,2})(\d[A-Z][A-Z])$/', $postalCode, $matches) !== 1) {
            return null;
        }

        return $matches[1].' '.$matches[2];
    }
}
