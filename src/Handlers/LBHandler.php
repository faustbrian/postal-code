<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;

use function mb_substr;
use function preg_match;

/**
 * Validates and formats postal codes for Lebanon (LB).
 *
 * Lebanon uses an 8-digit numeric postal code system formatted as two
 * groups of four digits separated by a space (NNNN NNNN). This handler
 * accepts input with or without spaces and formats it correctly.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class LBHandler implements PostalCodeHandler
{
    /**
     * Validates a postal code for Lebanon.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code for Lebanon.
     *
     * Accepts postal codes with or without spaces and returns them
     * in the standard NNNN NNNN format. Returns the original input
     * if the postal code is invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code (NNNN NNNN) or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the postal code format.
     *
     * @return string Description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'PostalCode format is NNNN NNNN, where N stands for a digit.';
    }

    /**
     * Validates and formats the postal code internally.
     *
     * Validates that the input consists of exactly 8 consecutive digits,
     * then formats it as two groups of 4 digits separated by a space.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code (NNNN NNNN) or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Must have exactly 8 digits
        if (preg_match('/^\d{8}$/', $postalCode) !== 1) {
            return null;
        }

        // Format as NNNN NNNN
        return mb_substr($postalCode, 0, 4).' '.mb_substr($postalCode, 4);
    }
}
