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
 * Validates and formats postal codes for French Guiana.
 *
 * French Guiana uses the French postal code system with a restricted
 * prefix. Postal codes consist of 5 digits and must start with "973"
 * (the département code for French Guiana). The format is 973NN where
 * each N represents a numeric digit from 0-9.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class GFHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches French Guiana's format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid for French Guiana, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to French Guiana's standards.
     *
     * Since French Guiana postal codes do not require any formatting (no spaces
     * or separators), this method returns the postal code as-is if valid, or
     * returns the original input unchanged if invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable description of the postal code format.
     *
     * @return string Description of French Guiana's postal code requirements
     */
    public function hint(): string
    {
        return 'PostalCode format is 973NN, where N stands for a digit.';
    }

    /**
     * Performs validation and formatting of the postal code.
     *
     * Validates that the postal code consists of exactly 5 digits and starts
     * with the French Guiana département prefix "973". Since French Guiana
     * postal codes require no formatting, the validated code is returned as-is.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The validated postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Must be exactly 5 digits
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        // Must start with French Guiana's département code
        if (!str_starts_with($postalCode, '973')) {
            return null;
        }

        return $postalCode;
    }
}
