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
 * Validates and formats postal codes for South Korea (KR).
 *
 * South Korean postal codes consist of 5 digits without any separator. The
 * current 5-digit system was introduced on August 1, 2015, replacing the
 * previous 6-digit system. The new format provides more efficient mail
 * sorting and delivery across the country.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes_in_South_Korea
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class KRHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches South Korean format.
     *
     * Checks if the postal code consists of exactly 5 consecutive digits
     * without any separator or other characters. This validates against the
     * current postal code system introduced in 2015.
     *
     * @param  string $postalCode The postal code string to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to South Korean standards.
     *
     * As South Korean postal codes do not require formatting (they are already
     * in their final 5-digit form), this method validates the postal code
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
     * @return string Description of the South Korean postal code format
     */
    public function hint(): string
    {
        return 'Since 2015, postalCodes consist of 5 digits, without separator.';
    }

    /**
     * Validates the postal code and returns it if valid.
     *
     * This internal method verifies the postal code consists of exactly
     * 5 digits according to the postal code system introduced in 2015.
     * Returns the postal code unchanged if valid, or null if validation
     * fails.
     *
     * @param  string      $postalCode The postal code to validate
     * @return null|string The postal code if valid, or null if validation fails
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
