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
use function substr_replace;

/**
 * Validates and formats postal codes in Sweden.
 *
 * Swedish postal codes consist of 5 digits formatted as NNN NN with a space separator
 * after the third digit. Valid postal codes range from 100 00 to 984 99.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Sweden
 */
final class SEHandler implements PostalCodeHandler
{
    /**
     * Validates a Swedish postal code.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   Returns true if the postal code is 5 digits within the range 10000-98499, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a Swedish postal code with space separator.
     *
     * Inserts a space after the third digit to produce the standard NNN NN format.
     * Invalid codes are returned as-is.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code (NNN NN), or the original value if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the postal code format.
     *
     * @return string Description of valid Swedish postal code format and range
     */
    public function hint(): string
    {
        return 'PostalCode format is NNN NN. The lowest number is 100 00 and the highest number is 984 99.';
    }

    /**
     * Performs validation and formatting of Swedish postal codes.
     *
     * Validates that the postal code consists of exactly 5 digits and falls within
     * Sweden's official postal code range (10000-98499). Formats valid codes by
     * inserting a space after the third digit.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The formatted postal code (NNN NN), or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        if ($postalCode < '10000' || $postalCode > '98499') {
            return null;
        }

        return substr_replace($postalCode, ' ', 3, 0);
    }
}
