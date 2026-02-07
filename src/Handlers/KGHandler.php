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
 * Validates and formats postal codes for Kyrgyzstan (KG).
 *
 * Kyrgyzstani postal codes consist of 6 digits without any separator. The
 * postal code system follows the format inherited from the Soviet postal
 * system, with codes assigned to different regions and cities throughout
 * the country.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 */
final class KGHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Kyrgyzstani format.
     *
     * Checks if the postal code consists of exactly 6 consecutive digits
     * without any separator or other characters.
     *
     * @param  string $postalCode The postal code string to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Kyrgyzstani standards.
     *
     * As Kyrgyzstani postal codes do not require formatting (they are already
     * in their final 6-digit form), this method validates the postal code
     * and returns it unchanged if valid, or returns the original input if
     * invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The postal code unchanged, or the original if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the expected postal code format.
     *
     * @return string Description of the Kyrgyzstani postal code format
     */
    public function hint(): string
    {
        return 'Postal codes in Kyrgyzstan are 6 digit numeric.';
    }

    /**
     * Validates the postal code and returns it if valid.
     *
     * This internal method verifies the postal code consists of exactly
     * 6 digits. Returns the postal code unchanged if valid, or null if
     * validation fails.
     *
     * @param  string      $postalCode The postal code to validate
     * @return null|string The postal code if valid, or null if validation fails
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{6}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
