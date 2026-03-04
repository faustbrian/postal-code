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
 * Validates and formats postal codes in Nicaragua.
 *
 * Nicaraguan postal codes are numeric, consisting of 5 digits without
 * separators. They follow a simple format with no additional validation
 * rules beyond the digit count requirement.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Nicaragua
 */
final class NIHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Nicaraguan format requirements.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Nicaraguan standards.
     *
     * Returns the postal code in its canonical format if valid, otherwise
     * returns the input unchanged. Nicaraguan postal codes do not require
     * formatting as they have no separator characters.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable description of the postal code format.
     *
     * @return string Description of the Nicaraguan postal code format requirements
     */
    public function hint(): string
    {
        return 'Postal codes in Nicaragua are 5 digit numeric.';
    }

    /**
     * Validates and formats the postal code, returning null if invalid.
     *
     * Performs regex validation to ensure the postal code consists of exactly
     * 5 digits. Since Nicaraguan postal codes require no formatting, valid codes
     * are returned unchanged.
     *
     * @param  string      $postalCode The postal code to process
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
