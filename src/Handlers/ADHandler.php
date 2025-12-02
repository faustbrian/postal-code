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
 * Validates and formats postal codes for Andorra (AD).
 *
 * Andorran postal codes consist of the letters "AD" followed by 3 digits,
 * without any separator (e.g., AD100, AD700). This handler accepts postal
 * codes both with and without the "AD" prefix, and always outputs the
 * standardized format with the prefix included.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Andorra
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class ADHandler implements PostalCodeHandler
{
    /**
     * Validates an Andorran postal code.
     *
     * Accepts postal codes with or without the "AD" prefix. The numeric
     * portion must be exactly 3 digits.
     *
     * @param  string $postalCode The normalized postal code to validate
     * @return bool   True if the postal code is valid for Andorra, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats an Andorran postal code with the "AD" prefix.
     *
     * Ensures the postal code is returned in the standard format with the
     * "AD" country prefix followed by 3 digits (e.g., AD500). If the postal
     * code is invalid, returns it unchanged as a fallback.
     *
     * @param  string $postalCode The normalized postal code to format
     * @return string The formatted postal code with "AD" prefix, or the original value if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a hint about the expected postal code format for Andorra.
     *
     * @return string A human-readable description of the Andorran postal code format
     */
    public function hint(): string
    {
        return 'PostalCodes consist of the letters AD, followed by 3 digits, without separator.';
    }

    /**
     * Performs the core validation and formatting logic.
     *
     * Strips the "AD" prefix if present, validates that the remaining portion
     * is exactly 3 digits, and returns the standardized format with the prefix.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The formatted postal code with "AD" prefix, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Remove "AD" prefix if present to normalize input
        if (str_starts_with($postalCode, 'AD')) {
            $postalCode = mb_substr($postalCode, 2);
        }

        // Validate that the numeric portion is exactly 3 digits
        if (preg_match('/^\d{3}$/', $postalCode) !== 1) {
            return null;
        }

        return 'AD'.$postalCode;
    }
}
