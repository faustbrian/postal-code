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
 * Handles postal code validation and formatting for Greece.
 *
 * Greece uses a 5-digit numeric postal code system. Postal codes are typically
 * formatted with a space separator in the pattern NNN NN where N represents a digit.
 * The first three digits identify the delivery area, while the last two digits
 * specify the delivery route within that area.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Greece
 */
final class GRHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code conforms to Greece's format.
     *
     * @param  string $postalCode The postal code string to validate
     * @return bool   Returns true if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Greece's standard format.
     *
     * Transforms a 5-digit postal code into the standard Greek format with a space
     * separator (NNN NN). If the postal code is invalid, returns the original input.
     *
     * @param  string $postalCode The postal code string to format
     * @return string The formatted postal code (NNN NN), or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about the postal code format.
     *
     * @return string Description of the expected postal code format
     */
    public function hint(): string
    {
        return 'PostalCodes consist of 5 digits, in the format NNN NN.';
    }

    /**
     * Validates and formats the postal code using the Greece pattern.
     *
     * Validates that the input consists of exactly 5 digits, then formats it by
     * inserting a space between the third and fourth digits to produce the standard
     * Greek postal code format.
     *
     * @param  string      $postalCode The postal code string to process
     * @return null|string The formatted postal code (NNN NN) if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
            return null;
        }

        return mb_substr($postalCode, 0, 3).' '.mb_substr($postalCode, 3);
    }
}
