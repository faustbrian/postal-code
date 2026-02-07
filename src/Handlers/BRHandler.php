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
 * Validates and formats postal codes in Brazil.
 *
 * Brazilian postal codes (CEP - Código de Endereçamento Postal) consist of
 * 8 digits formatted as NNNNN-NNN (5 digits, hyphen, 3 digits). The old 5-digit
 * format is not supported by this handler.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/C%C3%B3digo_de_Endere%C3%A7amento_Postal
 */
final class BRHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Brazilian postal code format.
     *
     * @param  string $postalCode Postal code to validate (8 digits without hyphen)
     * @return bool   True if the postal code is valid for Brazil, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard Brazilian format.
     *
     * Converts 8-digit input into the standard format with a hyphen separator
     * (NNNNN-NNN). If the postal code is invalid, returns the original input.
     *
     * @param  string $postalCode Postal code to format (8 digits without hyphen)
     * @return string Formatted postal code with hyphen separator or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the Brazilian postal code format.
     *
     * @return string Format description for end-user guidance
     */
    public function hint(): string
    {
        return 'Format is 5 digits, hyphen, 3 digits.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code consists of exactly 8 consecutive digits
     * and formats it with a hyphen after the fifth digit.
     *
     * @param  string      $postalCode Postal code to process
     * @return null|string Formatted postal code with hyphen if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^\d{8}$/', $postalCode) !== 1) {
            return null;
        }

        return mb_substr($postalCode, 0, 5).'-'.mb_substr($postalCode, 5);
    }
}
