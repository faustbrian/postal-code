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
 * Validates and formats postal codes in Slovenia.
 *
 * Slovenian postal codes consist of exactly 4 digits with no separator characters.
 * The system was introduced in 1991 following Slovenia's independence.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Slovenia
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class SIHandler implements PostalCodeHandler
{
    /**
     * Validates a Slovenian postal code.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   Returns true if the postal code consists of exactly 4 digits, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a Slovenian postal code.
     *
     * Since Slovenian postal codes have no separator, this returns the code unchanged
     * if valid, or the original input if invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The postal code unchanged if valid, or the original value if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the postal code format.
     *
     * @return string Description of valid Slovenian postal code format
     */
    public function hint(): string
    {
        return 'The codes consist of four digits written without separator characters.';
    }

    /**
     * Performs validation of Slovenian postal codes.
     *
     * Validates that the postal code matches exactly 4 digits with no other characters.
     *
     * @param  string      $postalCode The postal code to validate
     * @return null|string The postal code if valid, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
