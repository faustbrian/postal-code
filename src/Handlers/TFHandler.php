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
use function str_starts_with;

/**
 * Validates and formats postal codes in French Southern and Antarctic Territories.
 *
 * French Southern and Antarctic Territories uses postal codes in the 984xx range
 * (98400-98499), following the French 5-digit postal code system. These codes
 * are reserved for the territory but may not be actively used for all locations.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class TFHandler implements PostalCodeHandler
{
    /**
     * Validates whether a postal code matches French Southern and Antarctic Territories' format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code to French Southern and Antarctic Territories' standard format.
     *
     * Returns the formatted postal code if valid, or the original input if invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or original input
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the postal code format.
     *
     * @return string Format description for end users
     */
    public function hint(): string
    {
        return 'French codes in the 98400 range have been reserved, but do not seem to be in use at the moment.';
    }

    /**
     * Performs validation and formatting of the postal code.
     *
     * Validates that the code is 5 digits and begins with '984', ensuring
     * it falls within the reserved range for this territory.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        if (!str_starts_with($postalCode, '984')) {
            return null;
        }

        return $postalCode;
    }
}
