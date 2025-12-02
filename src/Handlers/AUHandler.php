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
 * Validates and formats postal codes for Australia (AU).
 *
 * Australian postal codes, also known as postcodes, consist of 4 digits
 * without separators. They range from 0200 to 9999 and are used across
 * all states and territories of Australia.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/PostalCodes_in_Australia
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class AUHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code is valid for Australia.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard format.
     *
     * Returns the properly formatted postal code if valid, otherwise returns
     * the original input unchanged. For Australia, the postal code is returned
     * as-is without modification since no reformatting is required.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about postal code format requirements.
     *
     * @return string Description of the postal code format for this country
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 4 digits, without separator.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code consists of exactly 4 digits. No additional
     * range validation is performed as Australia uses postcodes across the full
     * range of 4-digit numbers for different regions and delivery areas.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
