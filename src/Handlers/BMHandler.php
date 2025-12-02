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
 * Validates and formats postal codes in Bermuda.
 *
 * Bermuda uses two postal code formats:
 * - Street addresses: AA NN (two letters, space, two digits)
 * - P.O. Box addresses: AA AA (two letters, space, two letters)
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Bermuda
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class BMHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Bermudian postal code format.
     *
     * @param  string $postalCode Postal code to validate (format: AA NN or AA AA)
     * @return bool   True if the postal code is valid for Bermuda, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard Bermudian format.
     *
     * Adds a space separator between the two pairs of characters if not
     * already present. If the postal code is invalid, returns the original input.
     *
     * @param  string $postalCode Postal code to format (format: AANN or AAAA)
     * @return string Formatted postal code with space separator or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the Bermudian postal code format.
     *
     * @return string Format description for end-user guidance
     */
    public function hint(): string
    {
        return 'PostalCode formats are AA NN for street addresses, AA AA for P.O. Box addresses (A=letter, N=digit).';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code consists of two uppercase letters followed
     * by either two more letters (P.O. Box) or two digits (street address), and
     * formats it with a space separator.
     *
     * @param  string      $postalCode Postal code to process
     * @return null|string Formatted postal code with space separator if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^([A-Z]{2})([A-Z]{2}|\d{2})$/', $postalCode, $matches) !== 1) {
            return null;
        }

        return $matches[1].' '.$matches[2];
    }
}
