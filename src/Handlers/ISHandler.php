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
 * Validates and formats postal codes for Iceland (IS).
 *
 * Icelandic postal codes consist of 3 digits without any separator. The first
 * digit cannot be zero, as postal codes starting with zero are not valid in
 * Iceland's postal code system.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes_in_Iceland
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class ISHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Icelandic format.
     *
     * Checks if the postal code consists of exactly 3 digits and ensures
     * the first digit is not zero, as this is not valid in Iceland's postal
     * code system.
     *
     * @param  string $postalCode The postal code string to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Icelandic standards.
     *
     * As Icelandic postal codes do not require formatting (they are already
     * in their final 3-digit form), this method validates the postal code
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
     * @return string Description of the Icelandic postal code format
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 3 digits, without separator.';
    }

    /**
     * Validates the postal code and returns it if valid.
     *
     * This internal method performs two validation checks: first, it verifies
     * the postal code consists of exactly 3 digits; second, it ensures the
     * first digit is not zero, as Icelandic postal codes cannot start with
     * zero. Returns the postal code unchanged if valid, or null if validation
     * fails.
     *
     * @param  string      $postalCode The postal code to validate
     * @return null|string The postal code if valid, or null if validation fails
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{3}$/', $postalCode) !== 1) {
            return null;
        }

        // Icelandic postal codes cannot start with zero
        if ($postalCode[0] === '0') {
            return null;
        }

        return $postalCode;
    }
}
