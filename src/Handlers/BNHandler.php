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
 * Validates and formats postal codes in Brunei.
 *
 * Bruneian postal codes consist of two uppercase letters followed by 4 digits,
 * without any separator (format: AANNNN).
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Brunei
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class BNHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Bruneian postal code format.
     *
     * @param  string $postalCode Postal code to validate (format: AA####)
     * @return bool   True if the postal code is valid for Brunei, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard Bruneian format.
     *
     * Since Bruneian postal codes have no special formatting requirements,
     * this method returns the validated code unchanged. If the postal code
     * is invalid, returns the original input.
     *
     * @param  string $postalCode Postal code to format (format: AA####)
     * @return string Formatted postal code or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the Bruneian postal code format.
     *
     * @return string Format description for end-user guidance
     */
    public function hint(): string
    {
        return 'PostalCode format is two letters followed by 4 digits, without separator.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code consists of exactly two uppercase letters
     * followed by four digits.
     *
     * @param  string      $postalCode Postal code to process
     * @return null|string Validated postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^[A-Z]{2}\d{4}$/', $postalCode, $matches) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
