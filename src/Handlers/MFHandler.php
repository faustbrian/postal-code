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
 * Validates and formats postal codes for Saint Martin (MF).
 *
 * Saint Martin (French part) uses a single postal code: 97150. This code
 * represents the entire French collectivity, as it is part of the French
 * postal system. No other postal codes are valid for this territory.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class MFHandler implements PostalCodeHandler
{
    /**
     * Validates whether the postal code matches Saint Martin's format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Saint Martin's standards.
     *
     * Returns the formatted postal code if valid, otherwise returns the input unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about Saint Martin's postal code format.
     *
     * @return string Description of the postal code format
     */
    public function hint(): string
    {
        return 'This country uses a single postalCode, 97150.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code is exactly "97150", which is the only
     * valid postal code for Saint Martin (French part).
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The formatted postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if ($postalCode === '97150') {
            return $postalCode;
        }

        return null;
    }
}
