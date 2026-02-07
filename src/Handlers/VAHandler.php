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
 * Validates and formats postal codes for Vatican City.
 *
 * Vatican City, the world's smallest independent state, uses a single postal
 * code "00120" for all addresses. This code is part of the Italian postal
 * system, as Vatican City's postal services are operated by Poste Vaticane
 * in coordination with the Italian postal system.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class VAHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Vatican City's format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   Returns true if the postal code is exactly "00120", false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Vatican City's standard format.
     *
     * Since Vatican City uses only one postal code, this method validates
     * the input and returns it unchanged if valid. Invalid codes are returned unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or the original value if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable description of the accepted postal code format.
     *
     * @return string Format hint for Vatican City postal codes
     */
    public function hint(): string
    {
        return 'Single code used for all addresses. Part of the Italian postal code system.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Vatican City has only one valid postal code: "00120". Any other value
     * is rejected as invalid.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The postal code if it equals "00120", null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if ($postalCode === '00120') {
            return $postalCode;
        }

        return null;
    }
}
