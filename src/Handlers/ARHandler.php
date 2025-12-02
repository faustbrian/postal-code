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
 * Validates and formats postal codes for Argentina (AR).
 *
 * Argentina uses two postal code formats:
 * - Legacy format: 4 digits (e.g., 1234)
 * - New format: 1 letter + 4 digits + 3 letters (e.g., C1234ABC)
 *
 * Both formats use no separators. The new Código Postal Argentino (CPA)
 * format was introduced to provide more precise location identification.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Argentina
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class ARHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code is valid for Argentina.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard format.
     *
     * Returns the properly formatted postal code if valid, otherwise returns
     * the original input unchanged. For Argentina, the postal code is returned
     * as-is without modification since no reformatting is required.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about postal code format requirements.
     *
     * @return string Description of the postal code format for this country
     */
    public function hint(): string
    {
        return 'The postalCode is either 4 digits, or 1 letter + 4 digits + 3 letters, with no separators.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code matches one of the two accepted formats:
     * - 4 digits for legacy postal codes
     * - 1 uppercase letter, 4 digits, and 3 uppercase letters for CPA format
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^((\d{4})|([A-Z]\d{4}[A-Z]{3}))$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
