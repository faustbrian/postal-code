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
 * Validates and formats postal codes for the Falkland Islands.
 *
 * The Falkland Islands use a single postal code (FIQQ 1ZZ) for all
 * addresses across the territory. This handler validates and formats
 * the postal code to include the required space separator.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class FKHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code is the Falkland Islands code.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid for Falkland Islands, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Falkland Islands standards.
     *
     * Converts the unformatted code "FIQQ1ZZ" to the properly formatted
     * version "FIQQ 1ZZ" with a space separator. Returns the original
     * input unchanged if it's not the valid Falkland Islands postal code.
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
     * @return string Description of Falkland Islands' postal code requirements
     */
    public function hint(): string
    {
        return 'This country uses a single postalCode for all addresses.';
    }

    /**
     * Performs validation and formatting of the postal code.
     *
     * Checks if the postal code matches the single valid code for the
     * Falkland Islands (FIQQ1ZZ without spaces) and returns it formatted
     * with the proper space separator (FIQQ 1ZZ).
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code "FIQQ 1ZZ", or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if ($postalCode === 'FIQQ1ZZ') {
            return 'FIQQ 1ZZ';
        }

        return null;
    }
}
