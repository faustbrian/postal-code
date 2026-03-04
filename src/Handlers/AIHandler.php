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
 * Validates and formats postal codes for Anguilla (AI).
 *
 * Anguilla uses a single postal code for all addresses: AI-2640. This handler
 * accepts both the numeric portion "2640" and the full code "AI2640" (without
 * separator), and always outputs the standardized format "AI-2640" with the
 * country prefix and hyphen separator.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class AIHandler implements PostalCodeHandler
{
    /**
     * Validates an Anguilla postal code.
     *
     * Only accepts the specific value "2640" or "AI2640" since Anguilla
     * uses a single postal code for the entire territory.
     *
     * @param  string $postalCode The normalized postal code to validate
     * @return bool   True if the postal code is the valid Anguilla postal code, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats an Anguilla postal code with the standard "AI-2640" format.
     *
     * Accepts either "2640" or "AI2640" as input and returns the standardized
     * format "AI-2640" with the country prefix and hyphen. If the postal code
     * is invalid, returns it unchanged as a fallback.
     *
     * @param  string $postalCode The normalized postal code to format
     * @return string The formatted postal code "AI-2640", or the original value if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a hint about the expected postal code format for Anguilla.
     *
     * @return string A human-readable description of the Anguilla postal code
     */
    public function hint(): string
    {
        return 'Anguilla uses a single postalCode for all addresses.';
    }

    /**
     * Performs the core validation and formatting logic.
     *
     * Checks if the postal code matches either "2640" or "AI2640" and returns
     * the standardized format "AI-2640" if valid, or null if invalid.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The formatted postal code "AI-2640", or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Anguilla has a single postal code: AI-2640
        if ($postalCode === '2640' || $postalCode === 'AI2640') {
            return 'AI-2640';
        }

        return null;
    }
}
