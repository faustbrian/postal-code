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
 * Validates and formats postal codes for Gibraltar.
 *
 * Gibraltar uses a single postal code (GX11 1AA) for all addresses
 * across the territory. This handler validates and formats the postal
 * code to include the required space separator between the outward and
 * inward codes.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_addresses_in_Gibraltar
 */
final class GIHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code is the Gibraltar code.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid for Gibraltar, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Gibraltar's standards.
     *
     * Converts the unformatted code "GX111AA" to the properly formatted
     * version "GX11 1AA" with a space separator. Returns the original
     * input unchanged if it's not the valid Gibraltar postal code.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable description of the postal code format.
     *
     * @return string Description of Gibraltar's postal code requirements
     */
    public function hint(): string
    {
        return 'This country uses a single postalCode for all addresses.';
    }

    /**
     * Performs validation and formatting of the postal code.
     *
     * Checks if the postal code matches the single valid code for Gibraltar
     * (GX111AA without spaces) and returns it formatted with the proper
     * space separator (GX11 1AA).
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code "GX11 1AA", or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if ($postalCode === 'GX111AA') {
            return 'GX11 1AA';
        }

        return null;
    }
}
