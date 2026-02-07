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
 * Validates and formats postal codes for the Philippines.
 *
 * Philippines postal codes (ZIP codes) consist of exactly 4 digits without
 * separators. The system helps organize mail delivery across the country's
 * regions, provinces, and municipalities.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/List_of_ZIP_codes_in_the_Philippines
 */
final class PHHandler implements PostalCodeHandler
{
    /**
     * Validates a postal code against Philippines format requirements.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code matches the 4-digit format, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code to Philippines standard format.
     *
     * If the postal code is valid, returns it as-is (no formatting needed).
     * If invalid, returns the original input unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the expected postal code format.
     *
     * @return string Description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 4 digits, without separator.';
    }

    /**
     * Validates and formats the postal code, returning null if invalid.
     *
     * Checks that the postal code consists of exactly 4 digits using regex
     * pattern matching. No formatting transformation is applied as Philippines
     * postal codes have no separators or special formatting requirements.
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
