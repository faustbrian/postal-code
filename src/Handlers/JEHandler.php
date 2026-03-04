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
 * Validates and formats postal codes for Jersey (JE).
 *
 * Jersey postal codes follow the JE postcode area format with two possible
 * patterns: JE9 9AA or JE99 9AA. The format consists of the JE prefix followed
 * by 1-2 digits, a space, then one digit and two capital letters. Jersey uses
 * a subset of the UK postal code system.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/JE_postcode_area
 */
final class JEHandler implements PostalCodeHandler
{
    /**
     * Regular expression pattern for validating and parsing Jersey postal codes.
     *
     * The pattern captures two groups:
     * - Group 1: JE prefix followed by 1-2 digits (e.g., JE9 or JE99)
     * - Group 2: One digit followed by two capital letters (e.g., 9AA)
     */
    private const string PATTERN
        = '/^'
        .'(JE\d\d?)'
        .'(\d[A-Z][A-Z])'
        .'$/';

    /**
     * Validates whether the provided postal code matches Jersey format.
     *
     * Checks if the postal code follows one of the two valid Jersey formats
     * (JE9 9AA or JE99 9AA) with the correct combination of letters and digits.
     *
     * @param  string $postalCode The postal code string to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Jersey standards.
     *
     * Transforms the postal code into the standard Jersey format with a space
     * separator between the outward code (JE prefix + digits) and the inward
     * code (digit + two letters). If the postal code is invalid, returns it
     * unchanged.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or the original if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint about the expected postal code format.
     *
     * @return string Description of the Jersey postal code format
     */
    public function hint(): string
    {
        return 'PostalCodes can have two different formats:';
    }

    /**
     * Validates and formats the postal code in a single operation.
     *
     * This internal method uses regex pattern matching to validate the postal
     * code structure and extract its components. The pattern matches either
     * JE9 or JE99 prefix followed by the inward code. When valid, it formats
     * the postal code with a space separator between the two parts.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code, or null if validation fails
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match(self::PATTERN, $postalCode, $matches) !== 1) {
            return null;
        }

        return $matches[1].' '.$matches[2];
    }
}
