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
 * Validates and formats postal codes for Barbados (BB).
 *
 * Barbados uses 5-digit numeric postal codes prefixed with "BB". The postal
 * code system was introduced in 2007 to improve mail delivery efficiency
 * across the 11 parishes of Barbados.
 *
 * This handler accepts postal codes with or without the BB prefix and
 * always outputs the formatted version with the prefix included.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Barbados
 */
final class BBHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code is valid for Barbados.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard format (BBNNNNN).
     *
     * Returns the properly formatted postal code if valid, otherwise returns
     * the original input unchanged. Always outputs the postal code with the
     * BB prefix regardless of whether it was present in the input.
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
        return 'Postal codes in Barbados are 5 digit numeric, with BB prefix.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Strips the "BB" prefix if present, validates that the remaining portion
     * consists of exactly 5 digits, and returns the formatted postal code with
     * the BB prefix (no separator between prefix and digits).
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (str_starts_with($postalCode, 'BB')) {
            $postalCode = mb_substr($postalCode, 2);
        }

        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        return 'BB'.$postalCode;
    }
}
