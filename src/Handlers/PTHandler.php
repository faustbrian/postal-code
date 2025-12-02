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
 * Validates and formats postal codes for Portugal.
 *
 * Portuguese postal codes follow the format NNNN-NNN where N represents
 * a digit. The format consists of 7 digits total, with a hyphen separator
 * after the first 4 digits (e.g., 1000-001 for Lisbon).
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Portugal
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class PTHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code is valid for Portugal.
     *
     * @param  string $postalCode The postal code to validate (7 digits)
     * @return bool   Returns true if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard format for Portugal.
     *
     * Normalizes valid postal codes by adding the hyphen separator in the
     * correct position (e.g., "1000001" becomes "1000-001"). Invalid postal
     * codes are returned unchanged.
     *
     * @param  string $postalCode The postal code to format (7 digits)
     * @return string The formatted postal code or the original if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about postal code requirements.
     *
     * @return string A description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'PostalCode format is NNNN-NNN, N standing for a digit.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code consists of exactly 7 digits and
     * formats it with the standard hyphen separator after the 4th digit.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{7}$/', $postalCode) !== 1) {
            return null;
        }

        return mb_substr($postalCode, 0, 4).'-'.mb_substr($postalCode, 4);
    }
}
