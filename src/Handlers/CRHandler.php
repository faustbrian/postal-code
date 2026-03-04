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
 * Postal code handler for Costa Rica (ISO 3166-1 alpha-2: CR).
 *
 * Validates and formats postal codes according to Costa Rican standards.
 * Postal codes can be either 5 digits for district level or 9 digits
 * for street level (formatted as XXXXX-XXXX). This handler accepts both
 * formats and normalizes 9-digit codes to include the hyphen separator.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/List_of_districts_of_Costa_Rica
 */
final class CRHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches the format requirements.
     *
     * Checks if the postal code is either a valid 5-digit district-level
     * code or a 9-digit street-level code.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   Returns true if valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to standard conventions.
     *
     * Returns a properly formatted postal code if valid. For 9-digit codes,
     * adds a hyphen after the 5th digit (XXXXX-XXXX format). For invalid
     * codes, returns the original input unchanged to preserve user data.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the postal code format requirements.
     *
     * @return string A description of the expected postal code format
     */
    public function hint(): string
    {
        return 'Postal codes in Costa Rica are 5 digit numeric.';
    }

    /**
     * Performs validation and formatting of the postal code.
     *
     * Accepts 5-digit district codes or 9-digit street codes. For 9-digit
     * codes, inserts a hyphen after the 5th digit to produce the standard
     * XXXXX-XXXX format.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The validated and formatted postal code or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d+$/', $postalCode) !== 1) {
            return null;
        }

        $length = mb_strlen($postalCode);

        // District-level postal code (5 digits)
        if ($length === 5) {
            return $postalCode;
        }

        // Street-level postal code must be exactly 9 digits
        if ($length !== 9) {
            return null;
        }

        // Format as XXXXX-XXXX
        return mb_substr($postalCode, 0, 5).'-'.mb_substr($postalCode, 5);
    }
}
