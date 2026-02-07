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
 * Postal code handler for Cocos (Keeling) Islands (ISO 3166-1 alpha-2: CC).
 *
 * Validates and formats postal codes according to Cocos Islands standards.
 * Postal codes consist of exactly 4 numeric digits without separators.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class CCHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches the format requirements.
     *
     * Checks if the postal code conforms to the 4-digit format required
     * for Cocos Islands addresses.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   Returns true if valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to standard conventions.
     *
     * Returns a properly formatted postal code if valid, otherwise returns
     * the original input unchanged to preserve user data.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the postal code format requirements.
     *
     * @return string A description of the expected postal code format
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 4 digits, without separator.';
    }

    /**
     * Performs validation and formatting of the postal code.
     *
     * Returns the postal code if it matches the required 4-digit format,
     * or null if validation fails.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The validated postal code or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
