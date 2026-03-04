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
 * Validates and formats postal codes for Poland.
 *
 * Poland postal codes consist of 5 digits formatted as xy-zzz with a dash separator.
 *
 * The format has semantic meaning:
 * - First digit (x): Represents the postal district
 * - Second digit (y): Represents the major geographical subdivision of the district
 * - Last three digits (zzz): Represent the post office, or in large cities, a particular
 *   street, part of a street, or even a separate address
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class PLHandler implements PostalCodeHandler
{
    /**
     * Validates a postal code against Poland format requirements.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code matches the 5-digit format, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code to Poland standard format.
     *
     * Transforms a 5-digit postal code into the xy-zzz format with dash separator.
     * If the postal code is invalid, returns the original input unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code (xy-zzz) or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the expected postal code format.
     *
     * @return string Description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 5 digits in the following format: xy-zzz.';
    }

    /**
     * Validates and formats the postal code, returning null if invalid.
     *
     * First validates that the input consists of exactly 5 digits using regex
     * pattern matching. If valid, applies formatting by inserting a dash after
     * the second digit, transforming xxxxx into xx-xxx format. Uses mb_substr
     * for proper multibyte string handling.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code (xy-zzz) if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        return mb_substr($postalCode, 0, 2).'-'.mb_substr($postalCode, 2);
    }
}
