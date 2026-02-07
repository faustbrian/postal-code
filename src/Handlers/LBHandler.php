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

/**
 * Validates and formats postal codes for Lebanon (LB).
 *
 * Lebanon uses multiple official postal code formats:
 * - 4 digits for rural/basic areas (e.g., 1000 for Beirut region)
 * - 5 digits for placeholder codes (e.g., 00000)
 * - 8 digits (NNNN NNNN) for urban/specific addresses and P.O. Boxes
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://www.smarty.com/docs/cloud/international-street-api#lebanon
 */
final class LBHandler implements PostalCodeHandler
{
    /**
     * Validates a postal code for Lebanon.
     *
     * Accepts 4, 5, or 8 digit formats as per Lebanon's official
     * postal code standards.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code for Lebanon.
     *
     * 8-digit codes are formatted as NNNN NNNN, while 4 and 5 digit
     * codes are returned as-is.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or original input if invalid
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
        return 'PostalCode format is 4 digits (rural), 5 digits (placeholder), or NNNN NNNN (urban/P.O. Box).';
    }

    /**
     * Validates and formats the postal code internally.
     *
     * Accepts 4, 5, or 8 digit codes. Formats 8-digit codes
     * as NNNN NNNN, returns others as-is.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Accept 4, 5, or 8 digit codes
        if (preg_match('/^\d{4}$|^\d{5}$|^\d{8}$/', $postalCode) !== 1) {
            return null;
        }

        // Format 8-digit codes as NNNN NNNN
        if (preg_match('/^\d{8}$/', $postalCode) === 1) {
            return mb_substr($postalCode, 0, 4).' '.mb_substr($postalCode, 4);
        }

        // Return 4 and 5 digit codes as-is
        return $postalCode;
    }
}
