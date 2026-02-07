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
 * Validates and formats postal codes for British Antarctic Territory (AQ).
 *
 * British Antarctic Territory uses a single postal code (BIQQ 1ZZ) for all
 * addresses within the territory. This handler validates and formats this
 * specific postal code value.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class AQHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code is valid for British Antarctic Territory.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard format (BIQQ 1ZZ).
     *
     * Returns the properly formatted postal code if valid, otherwise returns
     * the original input unchanged.
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
        return 'This country uses a single postalCode for all addresses.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code matches the single valid value for
     * British Antarctic Territory and returns it in the standard format
     * with a space separator (BIQQ 1ZZ).
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if ($postalCode === 'BIQQ1ZZ') {
            return 'BIQQ 1ZZ';
        }

        return null;
    }
}
