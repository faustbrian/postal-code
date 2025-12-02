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
 * Validates and formats postal codes for Liberia (LR).
 *
 * Liberia uses a 4-digit numeric postal code system without separators.
 * All codes must consist of exactly four consecutive digits with no
 * spaces, hyphens, or other formatting characters.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class LRHandler implements PostalCodeHandler
{
    /**
     * Validates a postal code for Liberia.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code for Liberia.
     *
     * Returns the postal code unchanged if valid, or the original
     * input if invalid. Liberia postal codes require no formatting
     * as they are always 4 consecutive digits.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or original input if invalid
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
        return 'PostalCodes consist of 4 digits, without separator.';
    }

    /**
     * Validates and formats the postal code internally.
     *
     * Checks if the input matches the 4-digit pattern required for
     * Liberia postal codes. Returns the postal code unchanged if valid,
     * or null if validation fails.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
