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
 * Validates and formats postal codes for Réunion.
 *
 * Réunion, as a French overseas department, uses the French postal code
 * system. All postal codes consist of 5 digits starting with 974, without
 * any separator. This is a subset of the standard French postal code format.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class REHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code is valid for Réunion.
     *
     * @param  string $postalCode The postal code to validate (5 digits starting with 974)
     * @return bool   Returns true if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard format for Réunion.
     *
     * Since Réunion postal codes have no separator or special formatting,
     * valid codes are returned as-is. Invalid postal codes are returned unchanged.
     *
     * @param  string $postalCode The postal code to format (5 digits starting with 974)
     * @return string The formatted postal code or the original if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about postal code requirements.
     *
     * @return string A description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 5 digits, starting with 974, without separator.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code consists of exactly 5 digits and starts
     * with 974, which is the department code for Réunion. No formatting is
     * applied as the postal code has no separators.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        if (!str_starts_with($postalCode, '974')) {
            return null;
        }

        return $postalCode;
    }
}
