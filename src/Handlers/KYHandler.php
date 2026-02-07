<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;

use function mb_substr;
use function preg_match;
use function str_starts_with;

/**
 * Validates and formats postal codes for the Cayman Islands (KY).
 *
 * The Cayman Islands use a structured format: KYN-NNNN, where the first
 * digit after KY must be 1, 2, or 3, followed by a hyphen and four
 * additional digits. This handler accepts input with or without the
 * hyphen and formats it correctly.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_the_Cayman_Islands
 */
final class KYHandler implements PostalCodeHandler
{
    /**
     * Validates a postal code for the Cayman Islands.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code for the Cayman Islands.
     *
     * Accepts postal codes with or without hyphens and returns them
     * in the standard KYN-NNNN format. Returns the original input
     * if the postal code is invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code (KYN-NNNN) or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the postal code format.
     *
     * @return string Description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'PostalCode format is KYN-NNNN, where N are digits. The first digit can only be 1 to 3.';
    }

    /**
     * Validates and formats the postal code internally.
     *
     * Performs multi-step validation:
     * 1. Verifies the code starts with 'KY' prefix
     * 2. Checks that remaining characters form 5 consecutive digits
     * 3. Validates the first digit is between 1 and 3 (geographic constraint)
     * 4. Formats as KYN-NNNN with hyphen after the first digit
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code (KYN-NNNN) or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Must start with KY prefix
        if (!str_starts_with($postalCode, 'KY')) {
            return null;
        }

        // Extract digits after KY prefix
        $postalCode = mb_substr($postalCode, 2);

        // Must have exactly 5 digits
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        // First digit must be 1, 2, or 3 (valid district codes)
        if ($postalCode[0] < '1' || $postalCode[0] > '3') {
            return null;
        }

        // Format as KYN-NNNN
        return 'KY'.$postalCode[0].'-'.mb_substr($postalCode, 1);
    }
}
