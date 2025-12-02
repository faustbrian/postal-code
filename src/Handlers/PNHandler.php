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
 * Validates and formats postal codes for Pitcairn Islands.
 *
 * Pitcairn Islands uses a single postal code (PCRN 1ZZ) for all addresses
 * within the territory. This handler validates that the provided code matches
 * the official format and normalizes it by adding the required space separator.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class PNHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code is valid for Pitcairn Islands.
     *
     * @param  string $postalCode The postal code to validate (e.g., "PCRN1ZZ")
     * @return bool   Returns true if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard format for Pitcairn Islands.
     *
     * Normalizes valid postal codes by adding the space separator between
     * the area code and district code (e.g., "PCRN1ZZ" becomes "PCRN 1ZZ").
     * Invalid postal codes are returned unchanged.
     *
     * @param  string $postalCode The postal code to format (e.g., "PCRN1ZZ")
     * @return string The formatted postal code or the original if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about postal code requirements.
     *
     * @return string A description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'This country uses a single postalCode for all addresses.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Checks if the postal code matches the only valid code for Pitcairn Islands
     * and returns the properly formatted version with space separator.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if ($postalCode === 'PCRN1ZZ') {
            return 'PCRN 1ZZ';
        }

        return null;
    }
}
