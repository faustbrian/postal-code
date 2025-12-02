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
 * Validates and formats postal codes in Canada.
 *
 * Canadian postal codes follow the format ANA NAN (letter-digit-letter space
 * letter-digit-letter). Certain letters are excluded from postal codes:
 * D, F, I, O, Q, and U are never used, and W and Z are not used in the first
 * position.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Canada
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class CAHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Canadian postal code format.
     *
     * @param  string $postalCode Postal code to validate (format: ANANAN without space)
     * @return bool   True if the postal code is valid for Canada, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard Canadian format.
     *
     * Converts 6-character input into the standard format with a space separator
     * (ANA NAN). If the postal code is invalid, returns the original input.
     *
     * @param  string $postalCode Postal code to format (format: ANANAN without space)
     * @return string Formatted postal code with space separator or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the Canadian postal code format.
     *
     * @return string Format description for end-user guidance
     */
    public function hint(): string
    {
        return 'The format is ANA NAN, where A is a letter and N is a digit.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code matches the pattern (letter-digit-letter)
     * repeated twice, excludes forbidden letters (D, F, I, O, Q, U), and ensures
     * the first position is not W or Z. Formats with a space after the third
     * character.
     *
     * @param  string      $postalCode Postal code to process
     * @return null|string Formatted postal code with space separator if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        // Validate pattern: letter-digit-letter repeated twice
        // Excludes D, F, I, O, Q, U from all positions
        if (preg_match('/^([ABCEGHJ-NPRSTV-Z]\d){3}$/', $postalCode) !== 1) {
            return null;
        }

        // First position cannot be W or Z
        if ($postalCode[0] === 'W' || $postalCode[0] === 'Z') {
            return null;
        }

        return mb_substr($postalCode, 0, 3).' '.mb_substr($postalCode, 3);
    }
}
