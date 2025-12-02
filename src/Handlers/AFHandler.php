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
 * Validates and formats postal codes for Afghanistan (AF).
 *
 * Afghan postal codes consist of 4 digits without any separator. The first
 * two digits (ranging from 10-43) correspond to the province, while the last
 * two digits correspond to either the city/delivery zone (01-50) or the
 * district/delivery zone (51-99).
 *
 * This handler validates that the province code falls within the valid range
 * to ensure postal codes correspond to actual administrative regions in Afghanistan.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Afghanistan
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class AFHandler implements PostalCodeHandler
{
    /**
     * Validates an Afghan postal code.
     *
     * Ensures the postal code is exactly 4 digits and that the first two
     * digits (province code) fall within the valid range of 10-43.
     *
     * @param  string $postalCode The normalized postal code to validate
     * @return bool   True if the postal code is valid for Afghanistan, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats an Afghan postal code.
     *
     * Since Afghan postal codes do not use separators or prefixes, this
     * method validates the postal code and returns it unchanged if valid,
     * or returns the original value as a fallback if invalid.
     *
     * @param  string $postalCode The normalized postal code to format
     * @return string The postal code unchanged if valid, or the original value if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a hint about the expected postal code format for Afghanistan.
     *
     * @return string A human-readable description of the Afghan postal code format
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 4 digits, without separator.';
    }

    /**
     * Performs the core validation logic for Afghan postal codes.
     *
     * Validates that the postal code is exactly 4 digits and that the province
     * code (first two digits) falls within the valid range of 10-43, corresponding
     * to the actual provinces in Afghanistan.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The postal code if valid, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Validate format: exactly 4 digits
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        // Extract and validate province code (first 2 digits must be 10-43)
        $province = mb_substr($postalCode, 0, 2);

        if ($province < '10' || $province > '43') {
            return null;
        }

        return $postalCode;
    }
}
