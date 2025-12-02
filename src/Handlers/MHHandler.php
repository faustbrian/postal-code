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

/**
 * Validates and formats postal codes for Marshall Islands (MH).
 *
 * Marshall Islands uses U.S. ZIP codes within the range 96960-96970. The format
 * follows standard U.S. ZIP code conventions, supporting both 5-digit (NNNNN)
 * and 9-digit (NNNNN-NNNN) formats. The handler validates that the ZIP code
 * falls within the assigned range for Marshall Islands.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class MHHandler implements PostalCodeHandler
{
    /**
     * Validates whether the postal code matches Marshall Islands' format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Marshall Islands' standards.
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
     * Provides a human-readable hint about Marshall Islands' postal code format.
     *
     * @return string Description of the postal code format
     */
    public function hint(): string
    {
        return 'U.S. ZIP codes. Range 96960 - 96970.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code is all numeric, has a length of either 5 or 9 digits,
     * and that the base 5-digit ZIP code falls within the Marshall Islands assigned range
     * of 96960-96970. For 9-digit ZIP codes, returns the formatted NNNNN-NNNN format.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The formatted postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Validate all numeric characters
        if (preg_match('/^\d+$/', $postalCode) !== 1) {
            return null;
        }

        $length = mb_strlen($postalCode);

        // Extract base 5-digit ZIP code based on length
        if ($length === 5) {
            $zip = $postalCode;
        } elseif ($length === 9) {
            $zip = mb_substr($postalCode, 0, 5);
        } else {
            return null;
        }

        // Validate ZIP code is within Marshall Islands range
        if ($zip < '96960' || $zip > '96970') {
            return null;
        }

        // Return formatted based on original length
        if ($length === 5) {
            return $postalCode;
        }

        // Format 9-digit ZIP code with hyphen separator
        return $zip.'-'.mb_substr($postalCode, 5);
    }
}
