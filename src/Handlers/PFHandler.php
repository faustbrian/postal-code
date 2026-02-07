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
 * Validates and formats postal codes for French Polynesia.
 *
 * French Polynesia postal codes follow the format 987NN, where NN represents
 * two digits. All valid postal codes must begin with the prefix '987',
 * which is the designated code range for this French overseas collectivity.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class PFHandler implements PostalCodeHandler
{
    /**
     * Validates a postal code against French Polynesia format requirements.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code matches the 987NN format, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code to French Polynesia standard format.
     *
     * If the postal code is valid, returns it as-is (no formatting needed).
     * If invalid, returns the original input unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or original input if invalid
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
        return 'PostalCode format is 987NN, where N stands for a digit.';
    }

    /**
     * Validates and formats the postal code, returning null if invalid.
     *
     * Performs two validation checks: first ensures the postal code consists
     * of exactly 5 digits, then verifies it begins with the required '987'
     * prefix specific to French Polynesia. No formatting transformation is
     * applied as the postal codes have no separators.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        if (!str_starts_with($postalCode, '987')) {
            return null;
        }

        return $postalCode;
    }
}
