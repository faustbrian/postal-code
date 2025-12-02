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
 * Validates and formats postal codes for Lithuania (LT).
 *
 * Postal codes in Lithuania since 2005 are 5-digit numeric codes.
 * The ISO 3166-1 alpha-2 prefix is optional, with the format LT-NNNNN when included.
 * This handler accepts inputs both with and without the LT prefix and preserves
 * the prefix in the output only if it was present in the input.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Lithuania
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class LTHandler implements PostalCodeHandler
{
    /**
     * Validates whether the postal code matches Lithuania's format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Lithuania's standards.
     *
     * Returns the formatted postal code if valid, otherwise returns the input unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about Lithuania's postal code format.
     *
     * @return string Description of the postal code format
     */
    public function hint(): string
    {
        return 'Postal codes in Lithuania since 2005 are 5 digit numeric.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Accepts both prefixed (LT-NNNNN) and unprefixed (NNNNN) formats.
     * Validates that the code contains exactly 5 numeric digits and preserves
     * the LT prefix in the output only if it was present in the input.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The formatted postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        $length = mb_strlen($postalCode);
        $prefix = false;

        // Handle prefixed format (LT + 5 digits = 7 characters)
        if ($length === 7) {
            if (!str_starts_with($postalCode, 'LT')) {
                return null;
            }

            $postalCode = mb_substr($postalCode, 2);
            $prefix = true;
        } elseif (mb_strlen($postalCode) !== 5) {
            return null;
        }

        // Validate that remaining characters are all numeric
        if (preg_match('/^\d+$/', $postalCode) !== 1) {
            return null;
        }

        // Preserve prefix in output if it was present in input
        if ($prefix) {
            return 'LT-'.$postalCode;
        }

        return $postalCode;
    }
}
