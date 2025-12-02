<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;

/**
 * Handles postal code validation and formatting for South Georgia and the South Sandwich Islands.
 *
 * This British Overseas Territory uses a single postal code (SIQQ 1ZZ) for all
 * addresses. This unique postal code is shared across the entire territory due to
 * its small population and limited postal infrastructure.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class GSHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code is the valid code for this territory.
     *
     * @param  string $postalCode The postal code string to validate
     * @return bool   Returns true if the postal code is SIQQ1ZZ (with or without space), false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to the territory's standard format.
     *
     * Accepts the postal code with or without spacing and returns it in the standard
     * format with a space (SIQQ 1ZZ). Returns the original input if invalid.
     *
     * @param  string $postalCode The postal code string to format
     * @return string The formatted postal code (SIQQ 1ZZ), or the original input if invalid
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
        return 'This country uses a single postalCode for all addresses.';
    }

    /**
     * Validates and formats the postal code for South Georgia and the South Sandwich Islands.
     *
     * Only accepts the specific postal code SIQQ1ZZ (without space) and returns it
     * formatted with a space as SIQQ 1ZZ. Returns null for any other input.
     *
     * @param  string      $postalCode The postal code string to process
     * @return null|string The formatted postal code (SIQQ 1ZZ) if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if ($postalCode === 'SIQQ1ZZ') {
            return 'SIQQ 1ZZ';
        }

        return null;
    }
}
