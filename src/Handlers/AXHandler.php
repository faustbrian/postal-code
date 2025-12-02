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
use function str_starts_with;

/**
 * Validates and formats postal codes for Åland Islands (AX).
 *
 * Åland Islands use the Finnish postal system with postal codes consisting
 * of 5 digits, all starting with "22". When addressing mail from abroad, the
 * postal code may optionally include the "AX-" prefix (e.g., AX-22100).
 *
 * This formatter preserves the AX- prefix if present in the input but does
 * not add it if absent, maintaining the format preference of the user.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class AXHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code is valid for Åland Islands.
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
     * the original input unchanged. If the input includes the AX- prefix, it
     * is preserved in the output; otherwise only the 5-digit code is returned.
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
        return 'PostalCodes consist of 5 digits, starting with 22.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code is either:
     * - 5 digits starting with "22" (domestic format)
     * - "AX-" followed by 5 digits starting with "22" (international format)
     *
     * The handler strips and tracks the AX- prefix, validates the numeric portion,
     * ensures it starts with "22", and reapplies the prefix if it was present in
     * the original input.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        $length = mb_strlen($postalCode);
        $prefix = false;

        // Check for AX- prefix (international format)
        if ($length === 7) {
            if (!str_starts_with($postalCode, 'AX')) {
                return null;
            }

            $postalCode = mb_substr($postalCode, 2);
            $prefix = true;
        } elseif (mb_strlen($postalCode) !== 5) {
            return null;
        }

        // Validate numeric format
        if (preg_match('/^\d+$/', $postalCode) !== 1) {
            return null;
        }

        // Ensure postal code starts with 22 (Åland Islands range)
        if (!str_starts_with($postalCode, '22')) {
            return null;
        }

        // Preserve prefix if it was present in input
        if ($prefix) {
            return 'AX-'.$postalCode;
        }

        return $postalCode;
    }
}
