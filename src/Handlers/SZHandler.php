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
 * Validates and formats postal codes in Swaziland (Eswatini).
 *
 * Swaziland uses a 4-character alphanumeric postal code format consisting
 * of one uppercase letter followed by three digits (ANNN pattern). The letter
 * typically indicates the region or district within the country.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class SZHandler implements PostalCodeHandler
{
    /**
     * Validates whether a postal code matches Swaziland's format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code to Swaziland's standard format.
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
        return 'PostalCode format is ANNN, A standing for a letter and N for a digit.';
    }

    /**
     * Performs validation and formatting of the postal code.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^[A-Z]\d{3}$/', $postalCode, $matches) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
