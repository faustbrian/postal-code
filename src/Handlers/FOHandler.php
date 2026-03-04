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
 * Validates and formats postal codes for the Faroe Islands.
 *
 * Faroese postal codes consist of exactly 3 digits without any separator
 * or formatting. The format follows the pattern NNN where each N
 * represents a numeric digit from 0-9.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class FOHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Faroe Islands' format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid for Faroe Islands, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Faroe Islands standards.
     *
     * Since Faroese postal codes do not require any formatting (no spaces or
     * separators), this method returns the postal code as-is if valid, or
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
     * @return string Description of Faroe Islands' postal code requirements
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 3 digits, without separator.';
    }

    /**
     * Performs validation and formatting of the postal code.
     *
     * Validates that the postal code consists of exactly 3 digits. Since
     * Faroese postal codes require no formatting, the validated code is
     * returned as-is.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The validated postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{3}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
