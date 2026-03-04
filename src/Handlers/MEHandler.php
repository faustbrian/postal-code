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
 * Validates and formats postal codes for Montenegro (ME).
 *
 * Postal codes in Montenegro consist of exactly 5 digits without separators.
 * No country prefix is used in Montenegro's postal code system.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Montenegro
 */
final class MEHandler implements PostalCodeHandler
{
    /**
     * Validates whether the postal code matches Montenegro's format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Montenegro's standards.
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
     * Provides a human-readable hint about Montenegro's postal code format.
     *
     * @return string Description of the postal code format
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 5 digits, without separator.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code contains exactly 5 numeric digits.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The formatted postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
