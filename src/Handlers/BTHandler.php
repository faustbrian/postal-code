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
 * Validates and formats postal codes in Bhutan.
 *
 * Bhutanese postal codes consist of 5 digits without any separators.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class BTHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Bhutanese postal code format.
     *
     * @param  string $postalCode Postal code to validate (5 digits)
     * @return bool   True if the postal code is valid for Bhutan, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard Bhutanese format.
     *
     * Since Bhutanese postal codes have no special formatting requirements,
     * this method returns the validated code unchanged. If the postal code
     * is invalid, returns the original input.
     *
     * @param  string $postalCode Postal code to format (5 digits)
     * @return string Formatted postal code or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the Bhutanese postal code format.
     *
     * @return string Format description for end-user guidance
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 5 digits, without separator.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code consists of exactly 5 digits.
     *
     * @param  string      $postalCode Postal code to process
     * @return null|string Validated postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
