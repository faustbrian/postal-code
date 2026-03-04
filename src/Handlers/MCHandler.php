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
 * Validates and formats postal codes for Monaco (MC).
 *
 * Monaco, though an independent country, is integrated into the French postal
 * code system as if it were a French département. Postal codes consist of 5 digits
 * starting with "980", where 98000 is used for all physical addresses in the Principality,
 * and 98001-98099 are reserved for special delivery types.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_France#Monaco
 */
final class MCHandler implements PostalCodeHandler
{
    /**
     * Validates whether the postal code matches Monaco's format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Monaco's standards.
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
     * Provides a human-readable hint about Monaco's postal code format.
     *
     * @return string Description of the postal code format
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 5 digits, without separator.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code contains exactly 5 numeric digits and
     * begins with "980" to ensure it falls within Monaco's assigned range
     * within the French postal system.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The formatted postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Validate exactly 5 numeric digits
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        // Ensure postal code is within Monaco's assigned range (980xx)
        if (!str_starts_with($postalCode, '980')) {
            return null;
        }

        return $postalCode;
    }
}
