<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;

/**
 * Validates and formats postal codes for Saint Pierre and Miquelon.
 *
 * Saint Pierre and Miquelon uses a single postal code for the entire territory: 97500.
 * As a French overseas collectivity, it follows the French postal code system but
 * has only one designated code for all addresses in the archipelago.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class PMHandler implements PostalCodeHandler
{
    /**
     * Validates a postal code against Saint Pierre and Miquelon requirements.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is exactly '97500', false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code to Saint Pierre and Miquelon standard format.
     *
     * If the postal code is valid (97500), returns it as-is.
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
        return 'This country uses a single postalCode, 97500.';
    }

    /**
     * Validates and formats the postal code, returning null if invalid.
     *
     * Performs an exact string comparison to verify the postal code is '97500'.
     * This is the only valid postal code for Saint Pierre and Miquelon.
     * No formatting transformation is needed as there is only one valid value.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The postal code '97500' if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if ($postalCode === '97500') {
            return $postalCode;
        }

        return null;
    }
}
