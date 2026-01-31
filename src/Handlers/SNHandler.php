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
 * Validates and formats postal codes in Senegal.
 *
 * Senegalese postal codes consist of exactly 5 digits with no separator characters.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class SNHandler implements PostalCodeHandler
{
    /**
     * Validates a Senegalese postal code.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   Returns true if the postal code consists of exactly 5 digits, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a Senegalese postal code.
     *
     * Since Senegalese postal codes have no separator, this returns the code unchanged
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
     * @return string Description of valid Senegalese postal code format
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 5 digits, without separator.';
    }

    /**
     * Performs validation of Senegalese postal codes.
     *
     * Validates that the postal code matches exactly 5 digits with no other characters.
     *
     * @param  string      $postalCode The postal code to validate
     * @return null|string The postal code if valid, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
