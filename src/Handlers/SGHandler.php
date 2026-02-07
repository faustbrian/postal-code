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
 * Validates and formats postal codes in Singapore.
 *
 * Singaporean postal codes consist of exactly 6 digits with no separator characters.
 * Singapore uses a comprehensive 6-digit postal code system covering all addresses.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Singapore
 */
final class SGHandler implements PostalCodeHandler
{
    /**
     * Validates a Singaporean postal code.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   Returns true if the postal code consists of exactly 6 digits, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a Singaporean postal code.
     *
     * Since Singaporean postal codes have no separator, this returns the code unchanged
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
     * @return string Description of valid Singaporean postal code format
     */
    public function hint(): string
    {
        return 'Postal codes in Singapore consist of six digits, no separator.';
    }

    /**
     * Performs validation of Singaporean postal codes.
     *
     * Validates that the postal code matches exactly 6 digits with no other characters.
     *
     * @param  string      $postalCode The postal code to validate
     * @return null|string The postal code if valid, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{6}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
