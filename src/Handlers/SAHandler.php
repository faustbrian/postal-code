<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;

use function mb_strlen;
use function mb_substr;
use function preg_match;

/**
 * Validates and formats postal codes in Saudi Arabia.
 *
 * Saudi Arabia uses two distinct postal code formats:
 * - NNNNN: 5-digit format for PO Boxes
 * - NNNNN-NNNN: 9-digit format with hyphen separator for home delivery addresses
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class SAHandler implements PostalCodeHandler
{
    /**
     * Validates a Saudi Arabian postal code.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   Returns true if the postal code is valid (5 or 9 digits), false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a Saudi Arabian postal code with proper separator.
     *
     * Converts 9-digit postal codes to NNNNN-NNNN format with hyphen separator.
     * Returns 5-digit codes unchanged. Invalid codes are returned as-is.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or the original value if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the postal code format.
     *
     * @return string Description of valid Saudi Arabian postal code formats
     */
    public function hint(): string
    {
        return 'The postalCode format is NNNNN for PO Boxes and NNNNN-NNNN for home delivery, N standing for a digit.';
    }

    /**
     * Performs validation and formatting of Saudi Arabian postal codes.
     *
     * Validates that the postal code contains only digits and is either 5 or 9 digits long.
     * For 9-digit codes, inserts a hyphen separator after the 5th digit.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The formatted postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d+$/', $postalCode) !== 1) {
            return null;
        }

        $length = mb_strlen($postalCode);

        if ($length === 5) {
            return $postalCode;
        }

        if ($length === 9) {
            return mb_substr($postalCode, 0, 5).'-'.mb_substr($postalCode, -4);
        }

        return null;
    }
}
