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
 * Validates and formats postal codes in Czechia.
 *
 * Postal codes consist of 5 digits in the format: xxx xx (3 digits, space, 2 digits).
 * The first digit represents the postal district (1-7), the second digit provides
 * geographical subdivision, and remaining digits distinguish specific post offices
 * and post office boxes.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class CZHandler implements PostalCodeHandler
{
    /**
     * Validates a Czech postal code.
     *
     * Checks whether the postal code consists of 5 digits with a valid
     * district identifier (1-7) in the first position.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a Czech postal code to the standard format.
     *
     * Converts a 5-digit postal code to the format 'xxx xx' (3 digits, space, 2 digits).
     * Returns the original input if the postal code is invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a user-friendly hint about the postal code format.
     *
     * @return string A description of the expected postal code format
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 5 digits in the following format: xxx xx.';
    }

    /**
     * Validates and formats a Czech postal code.
     *
     * Performs validation and formatting in a single operation. Returns null
     * if the postal code is invalid, otherwise returns the formatted code.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        // Validate district identifier (must be 1-7)
        $district = $postalCode[0];

        if ($district < '1' || $district > '7') {
            return null;
        }

        return mb_substr($postalCode, 0, 3).' '.mb_substr($postalCode, 3);
    }
}
