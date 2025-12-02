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
 * Validates and formats postal codes for Malta (MT).
 *
 * Maltese postal codes follow the format "AAA NNNN" where A represents
 * an uppercase letter and N represents a digit. This handler accepts
 * postal codes without spacing and formats them with a space separator
 * between the letter and digit portions.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Malta
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class MTHandler implements PostalCodeHandler
{
    /**
     * Validates whether a postal code matches the Malta format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats a postal code to the standard Malta format.
     *
     * Returns the formatted postal code with a space separator if valid,
     * otherwise returns the original input unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code (e.g., "ABC 1234"), or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the postal code format.
     *
     * @return string The format description for end users
     */
    public function hint(): string
    {
        return 'PostalCode format is AAA NNNN, A standing for a letter and N standing for a digit.';
    }

    /**
     * Validates and formats the postal code according to Malta rules.
     *
     * Validates that the postal code consists of exactly 3 uppercase letters
     * followed by 4 digits. Formats the output with a space separator between
     * the letter and digit portions.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code with space separator, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^([A-Z]{3})(\d{4})$/', $postalCode, $matches) !== 1) {
            return null;
        }

        return $matches[1].' '.$matches[2];
    }
}
