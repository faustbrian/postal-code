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
 * Postal code handler for the Isle of Man (IM).
 *
 * The Isle of Man uses a postal code format similar to the UK system,
 * with "IM" prefix followed by 1-2 digits, a space, then a digit and
 * two letters. Formats: IM9 9AA or IM99 9AA.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/IM_postalCode_area
 */
final class IMHandler implements PostalCodeHandler
{
    /**
     * Regular expression pattern for validating Isle of Man postal codes.
     *
     * Matches the pattern IM followed by 1-2 digits (outward code), then
     * a digit and two uppercase letters (inward code). Example: IM1 1AA, IM99 9ZZ.
     */
    private const string PATTERN
        = '/^'
        .'(IM\d\d?)'
        .'(\d[A-Z][A-Z])'
        .'$/';

    /**
     * Validates an Isle of Man postal code.
     *
     * Postal codes are valid if they follow the IM format with 1-2 digits,
     * followed by a digit and two uppercase letters.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats an Isle of Man postal code to its standard representation.
     *
     * Ensures proper spacing between the outward and inward code sections.
     * Returns the original input unchanged if the postal code is invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code (with space) or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the postal code format.
     *
     * @return string Description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'PostalCodes can have two different formats: IM9 9AA or IM99 9AA. A stands for a capital letter, 9 stands for a digit.';
    }

    /**
     * Validates and formats the postal code.
     *
     * Verifies the postal code matches the Isle of Man format and returns
     * it with proper spacing between outward and inward codes, or null if invalid.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The validated postal code with space separator or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match(self::PATTERN, $postalCode, $matches) !== 1) {
            return null;
        }

        return $matches[1].' '.$matches[2];
    }
}
