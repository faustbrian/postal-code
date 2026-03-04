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
 * Validates and formats postal codes in Ecuador.
 *
 * Ecuadorian postal codes consist of 6 digits without separators or spaces.
 * The system helps organize mail delivery across Ecuador's provinces,
 * cities, and districts.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Ecuador
 */
final class ECHandler implements PostalCodeHandler
{
    /**
     * Validates an Ecuadorian postal code.
     *
     * Checks whether the postal code consists of exactly 6 digits.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats an Ecuadorian postal code to the standard format.
     *
     * Ecuadorian postal codes do not require formatting as they are stored
     * and displayed as 6 consecutive digits. Returns the original input
     * if the postal code is invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a user-friendly hint about the postal code format.
     *
     * @return string A description of the expected postal code format
     */
    public function hint(): string
    {
        return 'Postal codes in Ecuador have six numeric digits.';
    }

    /**
     * Validates and formats an Ecuadorian postal code.
     *
     * Performs validation in a single operation. Returns null if the postal
     * code is invalid, otherwise returns the validated code unchanged.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The validated postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{6}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
