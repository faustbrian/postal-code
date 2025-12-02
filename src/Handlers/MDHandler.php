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
use function str_starts_with;

/**
 * Validates and formats postal codes for Moldova (MD).
 *
 * Postal codes in Moldova consist of 4 numeric digits with the ISO 3166-1
 * alpha-2 country code prefix "MD". The format is MD-NNNN, where N represents
 * a digit. This handler accepts inputs both with and without the MD prefix but
 * always outputs the standardized MD-NNNN format.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Moldova
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class MDHandler implements PostalCodeHandler
{
    /**
     * Validates whether the postal code matches Moldova's format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Moldova's standards.
     *
     * Returns the formatted postal code with MD prefix if valid, otherwise returns
     * the input unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about Moldova's postal code format.
     *
     * @return string Description of the postal code format
     */
    public function hint(): string
    {
        return 'The postalCode format is MD-NNNN, where N stands for a digit.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Strips the MD prefix if present, validates that exactly 4 numeric digits
     * remain, and always returns the standardized MD-NNNN format.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The formatted postal code with MD prefix, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Strip MD prefix if present
        if (str_starts_with($postalCode, 'MD')) {
            $postalCode = mb_substr($postalCode, 2);
        }

        // Validate exactly 4 numeric digits
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        // Always return with MD prefix
        return 'MD-'.$postalCode;
    }
}
