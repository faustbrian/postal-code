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
 * Handles postal code validation and formatting for Guadeloupe.
 *
 * Guadeloupe uses the French postal code system with a specific prefix.
 * All postal codes follow the format 971NN where N represents a digit.
 * The prefix 971 is the overseas department code for Guadeloupe.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class GPHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code conforms to Guadeloupe's format.
     *
     * @param  string $postalCode The postal code string to validate
     * @return bool   Returns true if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Guadeloupe's standard format.
     *
     * Since Guadeloupe postal codes have no separators, this returns the postal code
     * unchanged if valid, or the original input if invalid.
     *
     * @param  string $postalCode The postal code string to format
     * @return string The formatted postal code, or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about the postal code format.
     *
     * @return string Description of the expected postal code format
     */
    public function hint(): string
    {
        return 'PostalCode format is 971NN, where N stands for a digit.';
    }

    /**
     * Validates and formats the postal code using the Guadeloupe pattern.
     *
     * Performs regex validation to ensure the postal code starts with 971 followed
     * by exactly 2 digits. Returns the formatted postal code on success, or null if invalid.
     *
     * @param  string      $postalCode The postal code string to process
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^971\d{2}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
