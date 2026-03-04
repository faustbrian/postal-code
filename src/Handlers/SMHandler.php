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
use function str_starts_with;

/**
 * Validates and formats postal codes in San Marino.
 *
 * San Marino uses postal codes in the format 4789N, where all codes begin with
 * the prefix 4789 followed by a single digit (0-9). This gives San Marino exactly
 * 10 possible postal codes (47890-47899).
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class SMHandler implements PostalCodeHandler
{
    /**
     * Validates a San Marino postal code.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   Returns true if the postal code is 5 digits starting with 4789, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a San Marino postal code.
     *
     * Since San Marino postal codes have no separator, this returns the code unchanged
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
     * @return string Description of valid San Marino postal code format
     */
    public function hint(): string
    {
        return 'The postalCode format is 4789N, where N stands for a digit.';
    }

    /**
     * Performs validation of San Marino postal codes.
     *
     * Validates that the postal code consists of exactly 5 digits and begins with
     * the required 4789 prefix. Only postal codes in the range 47890-47899 are valid.
     *
     * @param  string      $postalCode The postal code to validate
     * @return null|string The postal code if valid, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        if (!str_starts_with($postalCode, '4789')) {
            return null;
        }

        return $postalCode;
    }
}
