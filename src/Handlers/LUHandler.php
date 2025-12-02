<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;
use Cline\PostalCode\Support\StripPrefix;

use function preg_match;

/**
 * Validates and formats postal codes for Luxembourg (LU).
 *
 * Postal codes in Luxembourg consist of exactly 4 digits without separators.
 * The optional ISO 3166-1 alpha-2 prefix "L" may be present and will be stripped
 * during validation and formatting.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Luxembourg
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class LUHandler implements PostalCodeHandler
{
    use StripPrefix;

    /**
     * Validates whether the postal code matches Luxembourg's format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Luxembourg's standards.
     *
     * Returns the formatted postal code if valid, otherwise returns the input unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about Luxembourg's postal code format.
     *
     * @return string Description of the postal code format
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 4 digits, without separator.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Strips the optional "L" prefix if present, then validates that exactly
     * 4 numeric digits remain. Returns the 4-digit code without any prefix.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The formatted postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        $postalCode = $this->stripPrefix($postalCode, 'L');

        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
