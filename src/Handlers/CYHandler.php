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
use function preg_match;
use function str_starts_with;

/**
 * Postal code handler for Cyprus (ISO 3166-1 alpha-2: CY).
 *
 * Validates and formats postal codes according to Cypriot standards.
 * The postal code system covers the entire island with two formats:
 * - 4 digits for the Republic of Cyprus (southern Cyprus)
 * - 5 digits starting with "99" for Northern Cyprus (introduced in 2013)
 *
 * Note: The system is not used on mail to Northern Cyprus despite
 * postal codes being assigned to the territory.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Cyprus
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class CYHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches the format requirements.
     *
     * Checks if the postal code is either a 4-digit Republic of Cyprus code
     * or a 5-digit Northern Cyprus code starting with "99".
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
        return 'PostalCodes consist of 4 digits, without separator.';
    }

    /**
     * Performs validation and formatting of the postal code.
     *
     * Validates both 4-digit Republic of Cyprus codes and 5-digit Northern
     * Cyprus codes. For 5-digit codes, ensures they start with "99" prefix.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The validated postal code or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d+$/', $postalCode) !== 1) {
            return null;
        }

        $length = mb_strlen($postalCode);

        // Republic of Cyprus (4-digit format)
        if ($length === 4) {
            return $postalCode;
        }

        // Northern Cyprus must be exactly 5 digits
        if ($length !== 5) {
            return null;
        }

        // Northern Cyprus codes must start with "99"
        if (!str_starts_with($postalCode, '99')) {
            return null;
        }

        return $postalCode;
    }
}
