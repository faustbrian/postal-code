<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;
use Cline\PostalCode\Support\StripPrefix;

use function preg_match;

/**
 * Validates and formats postal codes for Austria (AT).
 *
 * Austrian postal codes consist of 4 digits without separators. The first
 * digit must be in the range 1-9 (never 0). Postal codes may optionally
 * include the country prefix "A-" when used internationally, which is
 * stripped during validation.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Austria
 */
final class ATHandler implements PostalCodeHandler
{
    use StripPrefix;

    /**
     * Validates whether the provided postal code is valid for Austria.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard format.
     *
     * Returns the properly formatted postal code if valid, otherwise returns
     * the original input unchanged. Removes the "A-" prefix if present and
     * returns the 4-digit code without separators.
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
        return 'PostalCodes consist of 4 digits, without separator. The first digit must be 1-9.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Strips the optional "A-" country prefix if present, then validates that
     * the postal code consists of exactly 4 digits with the first digit being
     * 1-9. This ensures postal codes do not start with zero, which is reserved
     * and not used in the Austrian postal system.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        $postalCode = $this->stripPrefix($postalCode, 'A');

        if (preg_match('/^[1-9]\d{3}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
