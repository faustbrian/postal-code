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
 * Validates and formats postal codes for Armenia (AM).
 *
 * Armenian postal codes consist of 4 digits without any separator or prefix.
 * The postal code system in Armenia was introduced after independence and
 * covers all regions including the capital Yerevan and surrounding provinces.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Armenia
 */
final class AMHandler implements PostalCodeHandler
{
    /**
     * Validates an Armenian postal code.
     *
     * Ensures the postal code is exactly 4 digits with no other characters.
     *
     * @param  string $postalCode The normalized postal code to validate
     * @return bool   True if the postal code is valid for Armenia, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats an Armenian postal code.
     *
     * Since Armenian postal codes do not use separators or prefixes, this
     * method validates the postal code and returns it unchanged if valid,
     * or returns the original value as a fallback if invalid.
     *
     * @param  string $postalCode The normalized postal code to format
     * @return string The postal code unchanged if valid, or the original value if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a hint about the expected postal code format for Armenia.
     *
     * @return string A human-readable description of the Armenian postal code format
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 4 digits, without separator.';
    }

    /**
     * Performs the core validation logic for Armenian postal codes.
     *
     * Validates that the postal code consists of exactly 4 digits.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The postal code if valid, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Validate format: exactly 4 digits
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
