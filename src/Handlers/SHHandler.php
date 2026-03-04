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
 * Validates and formats postal codes in Saint Helena, Ascension and Tristan da Cunha.
 *
 * This British Overseas Territory uses three distinct fixed postal codes:
 * - Saint Helena: STHL 1ZZ
 * - Ascension Island: ASCN 1ZZ
 * - Tristan da Cunha: TDCU 1ZZ
 *
 * Each territory has a single postal code for all addresses.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class SHHandler implements PostalCodeHandler
{
    /**
     * Validates a postal code for Saint Helena, Ascension or Tristan da Cunha.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   Returns true if the postal code matches one of the three valid codes, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code with proper spacing.
     *
     * Converts compact format (e.g., STHL1ZZ) to the standard format with space
     * separator (e.g., STHL 1ZZ). Invalid codes are returned as-is.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code with space, or the original value if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the postal code format.
     *
     * @return string Description of valid Saint Helena postal code format
     */
    public function hint(): string
    {
        return 'Saint Helena uses one code STHL 1ZZ';
    }

    /**
     * Performs validation and formatting of territory postal codes.
     *
     * Recognizes the three valid postal codes in compact format and returns them
     * formatted with the standard space separator. Only the specific codes for
     * Saint Helena, Ascension Island, and Tristan da Cunha are accepted.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The formatted postal code with space, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if ($postalCode === 'STHL1ZZ') {
            return 'STHL 1ZZ';
        }

        if ($postalCode === 'ASCN1ZZ') {
            return 'ASCN 1ZZ';
        }

        if ($postalCode === 'TDCU1ZZ') {
            return 'TDCU 1ZZ';
        }

        return null;
    }
}
