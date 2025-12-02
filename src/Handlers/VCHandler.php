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
 * Validates and formats postal codes for Saint Vincent and the Grenadines.
 *
 * Saint Vincent and the Grenadines uses postal codes in the format "VCNNNN",
 * where "VC" is the country prefix and "NNNN" represents four digits.
 * This handler accepts both formats with and without the "VC" prefix,
 * and always outputs the complete format with the prefix.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class VCHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Saint Vincent and the Grenadines format.
     *
     * @param  string $postalCode The postal code to validate (with or without VC prefix)
     * @return bool   Returns true if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Saint Vincent and the Grenadines standard format.
     *
     * Accepts postal codes with or without the "VC" prefix. Always outputs the
     * complete format with the "VC" prefix followed by 4 digits. Invalid codes
     * are returned unchanged.
     *
     * @param  string $postalCode The postal code to format (with or without VC prefix)
     * @return string The formatted postal code with VC prefix, or the original value if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable description of the accepted postal code format.
     *
     * @return string Format hint for Saint Vincent and the Grenadines postal codes
     */
    public function hint(): string
    {
        return 'The postalCode format is VCNNNN, where N represents a digit.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Strips the "VC" prefix if present, validates that the remaining part consists
     * of exactly 4 digits, then returns the formatted code with the "VC" prefix.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code with VC prefix if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        // Strip VC prefix if present to normalize input
        if (str_starts_with($postalCode, 'VC')) {
            $postalCode = mb_substr($postalCode, 2);
        }

        // Validate that the code consists of exactly 4 digits
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        // Return formatted code with VC prefix
        return 'VC'.$postalCode;
    }
}
