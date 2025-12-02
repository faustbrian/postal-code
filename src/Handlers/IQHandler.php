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
 * Postal code handler for Iraq (IQ).
 *
 * Iraq uses a 5-digit postal code system without separators.
 * The postal code system was established to organize mail delivery
 * across the country's governorates and districts.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Iraq
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class IQHandler implements PostalCodeHandler
{
    /**
     * Validates an Iraqi postal code.
     *
     * Postal codes are valid if they consist of exactly 5 digits.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats an Iraqi postal code to its standard representation.
     *
     * Returns the postal code without modifications if valid, or returns
     * the original input unchanged if invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the postal code format.
     *
     * @return string Description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 5 digits, without separator.';
    }

    /**
     * Validates and formats the postal code.
     *
     * Checks if the postal code matches the required 5-digit pattern.
     * Returns the postal code unchanged if valid, or null if invalid.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The validated postal code or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
