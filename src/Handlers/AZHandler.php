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
 * Validates and formats postal codes for Azerbaijan (AZ).
 *
 * Azerbaijan uses the format "AZ NNNN" where N represents a digit. The
 * postal code system consists of 4 digits prefixed with the country code
 * "AZ" and a space separator.
 *
 * This handler accepts postal codes with or without the AZ prefix and
 * always outputs the formatted version with the prefix included.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Azerbaijan
 */
final class AZHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code is valid for Azerbaijan.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard format (AZ NNNN).
     *
     * Returns the properly formatted postal code if valid, otherwise returns
     * the original input unchanged. Always outputs the postal code with the
     * AZ prefix and space separator regardless of input format.
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
        return 'The postalCode format is AZ NNNN, where N represents a digit.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Strips the "AZ" prefix if present (with or without space separator),
     * validates that the remaining portion consists of exactly 4 digits,
     * and returns the formatted postal code with the standard "AZ " prefix.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (str_starts_with($postalCode, 'AZ')) {
            $postalCode = mb_substr($postalCode, 2);
        }

        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        return 'AZ '.$postalCode;
    }
}
