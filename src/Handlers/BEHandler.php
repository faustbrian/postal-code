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
 * Validates and formats postal codes in Belgium.
 *
 * Belgian postal codes consist of 4 digits without separators. They may
 * optionally be prefixed with "B-" or "B" in international contexts, which
 * this handler strips during validation and formatting.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Belgium
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class BEHandler implements PostalCodeHandler
{
    use StripPrefix;

    /**
     * Validates whether the provided postal code matches Belgian postal code format.
     *
     * @param  string $postalCode Postal code to validate, optionally prefixed with "B-" or "B"
     * @return bool   True if the postal code is valid for Belgium, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard Belgian format.
     *
     * Strips any "B-" or "B" prefix and returns the 4-digit code. If the postal
     * code is invalid, returns the original input unchanged.
     *
     * @param  string $postalCode Postal code to format, optionally prefixed with "B-" or "B"
     * @return string Formatted postal code (4 digits) or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the Belgian postal code format.
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
     * Strips the "B-" or "B" prefix from the postal code and validates that
     * the remaining value consists of exactly 4 digits.
     *
     * @param  string      $postalCode Postal code to process
     * @return null|string Formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        $postalCode = $this->stripPrefix($postalCode, 'B');

        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            return null;
        }

        return $postalCode;
    }
}
