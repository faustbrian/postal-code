<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;

use function in_array;
use function preg_match;

/**
 * Validates and formats postal codes in the Netherlands.
 *
 * Dutch postal codes follow the format NNNN AA, where N represents a digit
 * (1-9 for the first digit, 0-9 for others) and A represents a letter.
 * The letter combinations 'SS', 'SD', and 'SA' are explicitly excluded
 * from valid postal codes due to historical and administrative reasons.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_the_Netherlands
 */
final class NLHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code matches Dutch format requirements.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to Dutch standards.
     *
     * Returns the postal code in its canonical format (NNNN AA with a space)
     * if valid, otherwise returns the input unchanged. Dutch postal codes
     * are normalized to include a space between the numeric and letter portions.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable description of the postal code format.
     *
     * @return string Description of the Dutch postal code format requirements
     */
    public function hint(): string
    {
        return 'PostalCode format is NNNN AA, where N stands for a digit and A for a letter.';
    }

    /**
     * Validates and formats the postal code, returning null if invalid.
     *
     * Performs regex validation to ensure the postal code matches the pattern
     * NNNN AA (with optional space input), validates that forbidden letter
     * combinations (SS, SD, SA) are not used, and returns the formatted code
     * with proper spacing.
     *
     * @param  string      $postalCode The postal code to process
     * @return null|string The validated and formatted postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match('/^([1-9]\d{3})([A-Z]{2})$/', $postalCode, $matches) !== 1) {
            return null;
        }

        // Reject forbidden letter combinations used for special purposes
        if (in_array($matches[2], ['SS', 'SD', 'SA'], true)) {
            return null;
        }

        return $matches[1].' '.$matches[2];
    }
}
