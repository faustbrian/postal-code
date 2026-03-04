<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;

use function in_array;
use function mb_substr;
use function preg_match;

/**
 * Postal code handler for Colombia (ISO 3166-1 alpha-2: CO).
 *
 * Validates and formats postal codes according to Colombian standards.
 * Postal codes consist of exactly 6 numeric digits without separators.
 * The first 2 digits represent the department code and must be one of
 * the valid department identifiers.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://es.wikipedia.org/wiki/Anexo:C%C3%B3digos_postales_de_Colombia
 */
final class COHandler implements PostalCodeHandler
{
    /**
     * Valid Colombian department codes used as the first two digits of postal codes.
     *
     * Each code corresponds to a specific Colombian department. The remaining
     * 4 digits provide more granular location information within the department.
     */
    private const array DEPARTMENTS = [
        '05', '08', '11', '13',
        '15', '17', '18', '19',
        '20', '23', '25', '27',
        '41', '44', '47', '50',
        '52', '54', '63', '66',
        '68', '70', '73', '76',
        '81', '85', '86', '88',
        '91', '94', '95', '97',
        '99',
    ];

    /**
     * Validates whether the provided postal code matches the format requirements.
     *
     * Checks if the postal code conforms to the 6-digit format, does not
     * contain 0000 in the last 4 digits, and has a valid department code
     * in the first 2 digits.
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
     * Returns a properly formatted postal code if valid, otherwise returns
     * the original input unchanged to preserve user data.
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
        return 'Postal codes in Colombia are 6 digit numeric.';
    }

    /**
     * Performs validation and formatting of the postal code.
     *
     * Validates the postal code structure including the 6-digit format,
     * prohibition of 0000 in positions 3-6, and verification that the
     * department code (first 2 digits) is valid.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The validated postal code or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Validate 6-digit format and reject codes ending in 0000
        if (preg_match('/^\d{2}(?!0000)\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        $department = mb_substr($postalCode, 0, 2);

        if (!in_array($department, self::DEPARTMENTS, true)) {
            return null;
        }

        return $postalCode;
    }
}
