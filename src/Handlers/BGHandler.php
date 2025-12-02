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
 * Validates and formats postal codes in Bulgaria.
 *
 * Bulgarian postal codes consist of 4 digits without separators.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Bulgaria
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class BGHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Bulgarian postal code format.
     *
     * @param  string $postalCode Postal code to validate (4 digits)
     * @return bool   True if the postal code is valid for Bulgaria, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard Bulgarian format.
     *
     * Since Bulgarian postal codes have no special formatting requirements,
     * this method returns the validated code unchanged. If the postal code
     * is invalid, returns the original input.
     *
     * @param  string $postalCode Postal code to format (4 digits)
     * @return string Formatted postal code or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the Bulgarian postal code format.
     *
     * @return string Format description for end-user guidance
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 4 digits, without separator.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code consists of exactly 4 digits.
     *
     * @param  string      $postalCode Postal code to process
     * @return null|string Validated postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
