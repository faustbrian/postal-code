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
 * Validates and formats postal codes in Saint Barthélemy.
 *
 * Saint Barthélemy uses a single postal code for the entire territory: 97133.
 * This code is shared with the French overseas collectivity system.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class BLHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Saint Barthélemy's postal code.
     *
     * @param  string $postalCode Postal code to validate (must be "97133")
     * @return bool   True if the postal code is "97133", false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard Saint Barthélemy format.
     *
     * Since only one postal code is valid ("97133"), this method returns
     * the validated code unchanged. If the postal code is invalid, returns
     * the original input.
     *
     * @param  string $postalCode Postal code to format (must be "97133")
     * @return string Formatted postal code or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the Saint Barthélemy postal code format.
     *
     * @return string Format description for end-user guidance
     */
    public function hint(): string
    {
        return 'This country uses a single postalCode, 97133.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code is exactly "97133", the only valid
     * postal code for Saint Barthélemy.
     *
     * @param  string      $postalCode Postal code to process
     * @return null|string Validated postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if ($postalCode === '97133') {
            return $postalCode;
        }

        return null;
    }
}
