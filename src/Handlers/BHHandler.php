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
 * Validates and formats postal codes in Bahrain.
 *
 * Bahraini postal codes (formally known as block numbers) are 3 or 4 digit
 * numeric codes. Valid codes range from 101 to 1216 with gaps in the range.
 * The first 1 or 2 digits refer to one of the 12 municipalities of the country.
 * Codes ending in "00" are invalid.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class BHHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Bahraini postal code format.
     *
     * @param  string $postalCode Postal code to validate (3-4 digits)
     * @return bool   True if the postal code is valid for Bahrain, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard Bahraini format.
     *
     * Since Bahraini postal codes have no special formatting requirements,
     * this method returns the validated code unchanged. If the postal code
     * is invalid, returns the original input.
     *
     * @param  string $postalCode Postal code to format (3-4 digits)
     * @return string Formatted postal code or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the Bahraini postal code format.
     *
     * @return string Format description for end-user guidance
     */
    public function hint(): string
    {
        return 'Valid post code numbers are 101 to 1216 with gaps in the range. Known as block number formally.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code matches the pattern (1 or 2 digits)(2 digits),
     * where the first 1-2 digits represent a municipality (1-12) and the last
     * 2 digits are not "00".
     *
     * @param  string      $postalCode Postal code to process
     * @return null|string Validated postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^(1?\d)(\d{2})$/', $postalCode, $matches) !== 1) {
            return null;
        }

        // Validate municipality number is between 1 and 12
        $municipality = (int) $matches[1];

        if ($municipality < 1 || $municipality > 12) {
            return null;
        }

        // Block numbers ending in "00" are invalid
        if ($matches[2] === '00') {
            return null;
        }

        return $postalCode;
    }
}
