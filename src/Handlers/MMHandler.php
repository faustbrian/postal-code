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
 * Validates and formats postal codes for Myanmar (MM).
 *
 * Postal codes in Myanmar consist of exactly 5 digits without any
 * separators or special formatting. This handler validates the format
 * and returns the postal code unchanged when valid.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Myanmar
 */
final class MMHandler implements PostalCodeHandler
{
    /**
     * Validates whether a postal code matches the Myanmar format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code to the standard Myanmar format.
     *
     * Returns the formatted postal code if valid, otherwise returns
     * the original input unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the postal code format.
     *
     * @return string The format description for end users
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 5 digits, without separator.';
    }

    /**
     * Validates and formats the postal code according to Myanmar rules.
     *
     * Ensures the postal code consists of exactly 5 digits. No formatting
     * changes are applied as Myanmar postal codes have no separators.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The validated postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
