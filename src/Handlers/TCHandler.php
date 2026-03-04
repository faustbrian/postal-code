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
 * Validates and formats postal codes in Turks and Caicos Islands.
 *
 * Turks and Caicos Islands uses a single postal code (TKCA 1ZZ) for all
 * addresses throughout the territory. This handler accepts the code with
 * or without a space and returns it in the standardized format with a space.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class TCHandler implements PostalCodeHandler
{
    /**
     * Validates whether a postal code matches Turks and Caicos Islands' format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code to Turks and Caicos Islands' standard format.
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
        return 'This country uses a single postalCode for all addresses: TKCA1ZZ.';
    }

    /**
     * Performs validation and formatting of the postal code.
     *
     * Accepts the postal code without spaces (TKCA1ZZ) and returns it
     * formatted with a space (TKCA 1ZZ) for standardization.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if ($postalCode === 'TKCA1ZZ') {
            return 'TKCA 1ZZ';
        }

        return null;
    }
}
