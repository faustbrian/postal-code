<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;

use function mb_substr;
use function preg_match;
use function str_starts_with;

/**
 * Validates and formats postal codes for Latvia (LV).
 *
 * Postal codes in Latvia consist of 4 numeric digits with a mandatory
 * ISO 3166-1 alpha-2 country code prefix. The format is LV-NNNN, where N
 * represents a digit. This handler accepts inputs both with and without
 * the LV prefix but always outputs the standardized LV-NNNN format.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Latvia
 */
final class LVHandler implements PostalCodeHandler
{
    /**
     * Validates whether the postal code matches Latvia's format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Latvia's standards.
     *
     * Returns the formatted postal code with LV prefix if valid, otherwise returns
     * the input unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about Latvia's postal code format.
     *
     * @return string Description of the postal code format
     */
    public function hint(): string
    {
        return 'Postal codes in Latvia are 4 digit numeric and use a mandatory country code (LV) in front.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Strips the LV prefix if present, validates that exactly 4 numeric digits
     * remain, and always returns the standardized LV-NNNN format.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The formatted postal code with LV prefix, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Strip LV prefix if present
        if (str_starts_with($postalCode, 'LV')) {
            $postalCode = mb_substr($postalCode, 2);
        }

        // Validate exactly 4 numeric digits
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        // Always return with mandatory LV prefix
        return 'LV-'.$postalCode;
    }
}
