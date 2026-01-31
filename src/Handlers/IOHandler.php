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
 * Postal code handler for British Indian Ocean Territory (IO).
 *
 * The British Indian Ocean Territory uses a single postal code (BBND 1ZZ)
 * for all addresses. This is the only valid postal code for the territory.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class IOHandler implements PostalCodeHandler
{
    /**
     * Validates a British Indian Ocean Territory postal code.
     *
     * Only the postal code "BBND1ZZ" (formatted as "BBND 1ZZ") is valid
     * for this territory.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a British Indian Ocean Territory postal code.
     *
     * Returns the properly formatted postal code "BBND 1ZZ" if the input
     * matches, or returns the original input unchanged if invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code "BBND 1ZZ" or original input if invalid
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
        return 'This country uses a single postalCode for all addresses.';
    }

    /**
     * Validates and formats the postal code.
     *
     * Checks if the input matches "BBND1ZZ" and returns it formatted
     * with a space as "BBND 1ZZ". Returns null for any other input.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code "BBND 1ZZ" or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if ($postalCode === 'BBND1ZZ') {
            return 'BBND 1ZZ';
        }

        return null;
    }
}
