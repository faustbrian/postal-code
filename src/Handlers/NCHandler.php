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
 * Validates and formats postal codes in New Caledonia.
 *
 * As an Overseas Collectivity of France, New Caledonia uses French postal
 * codes in the range 98800-98890. Postal codes consist of 5 digits and
 * must fall within this specific range to be considered valid.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class NCHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches New Caledonian format requirements.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to New Caledonian standards.
     *
     * Returns the postal code in its canonical format if valid, otherwise
     * returns the input unchanged. New Caledonian postal codes do not require
     * formatting as they have no separator characters.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable description of the postal code format.
     *
     * @return string Description of the New Caledonian postal code format requirements
     */
    public function hint(): string
    {
        return 'Overseas Collectivity of France. French codes used. Range 98800 - 98890.';
    }

    /**
     * Validates and formats the postal code, returning null if invalid.
     *
     * Performs regex validation to ensure the postal code consists of exactly
     * 5 digits, then validates that it falls within the New Caledonian range
     * of 98800-98890. Valid codes are returned unchanged.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The validated postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        // Validate postal code is within New Caledonia's assigned range
        if ($postalCode < '98800' || $postalCode > '98890') {
            return null;
        }

        return $postalCode;
    }
}
