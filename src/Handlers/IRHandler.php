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
 * Validates and formats postal codes for Iran (IR).
 *
 * Iranian postal codes consist of 10 consecutive digits that are formatted
 * with a space separator in the middle (NNNNN NNNNN format). This handler
 * validates the numeric structure and applies the standard formatting.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class IRHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Iranian format.
     *
     * Checks if the postal code consists of exactly 10 digits without any
     * separator. The validation is strict and requires all characters to be
     * numeric digits.
     *
     * @param  string $postalCode The postal code string to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Iranian standards.
     *
     * Transforms a 10-digit postal code into the standard Iranian format
     * with a space separator in the middle (NNNNN NNNNN). If the postal
     * code is invalid, returns it unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or the original if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the expected postal code format.
     *
     * @return string Description of the Iranian postal code format
     */
    public function hint(): string
    {
        return 'PostalCode format is NNNNN NNNNN, where N stands for a digit.';
    }

    /**
     * Validates and formats the postal code in a single operation.
     *
     * This internal method handles both validation and formatting. It verifies
     * the postal code consists of exactly 10 digits, then splits it into two
     * 5-digit groups separated by a space using multibyte string functions to
     * ensure proper handling of character encodings.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code, or null if validation fails
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{10}$/', $postalCode) !== 1) {
            return null;
        }

        return mb_substr($postalCode, 0, 5).' '.mb_substr($postalCode, 5);
    }
}
