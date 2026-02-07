<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Handlers;

use Cline\PostalCode\Contracts\PostalCodeHandler;

use function mb_strlen;
use function mb_substr;
use function preg_match;

/**
 * Validates and formats postal codes for American Samoa (AS).
 *
 * Mail service in American Samoa is fully integrated with the United States
 * Postal Service. All addresses in American Samoa use ZIP code 96799, with
 * optional ZIP+4 format for more precise delivery (96799-XXXX).
 *
 * This handler accepts both 5-digit ZIP codes and 9-digit ZIP+4 codes,
 * formatting the latter with a hyphen separator.
 *
 * @author Brian Faust <brian@cline.sh>
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_American_Samoa
 */
final class ASHandler implements PostalCodeHandler
{
    /**
     * Validates whether the provided postal code is valid for American Samoa.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code to the standard format.
     *
     * Returns the properly formatted postal code if valid, otherwise returns
     * the original input unchanged. For 9-digit codes, formats as ZIP+4 with
     * hyphen separator (96799-XXXX).
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code or the original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Provides a human-readable hint about postal code format requirements.
     *
     * @return string Description of the postal code format for this country
     */
    public function hint(): string
    {
        return 'Mail service in American Samoa is fully integrated with the United States Postal Service.';
    }

    /**
     * Performs the actual validation and formatting logic.
     *
     * Validates that the postal code is either:
     * - 5-digit ZIP code matching 96799
     * - 9-digit ZIP+4 code starting with 96799
     *
     * For 9-digit codes, extracts the base ZIP and +4 extension, then formats
     * with a hyphen separator. Only accepts the specific ZIP code assigned to
     * American Samoa (96799).
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code if valid, null otherwise
     */
    private function doFormat(string $postalCode): ?string
    {
        $length = mb_strlen($postalCode);

        // Handle 5-digit ZIP code
        if ($length === 5) {
            if ($postalCode !== '96799') {
                return null;
            }

            return $postalCode;
        }

        // Validate 9-digit ZIP+4 format
        if (preg_match('/^\d{9}$/', $postalCode) !== 1) {
            return null;
        }

        $zip = mb_substr($postalCode, 0, 5);

        if ($zip !== '96799') {
            return null;
        }

        $plusFour = mb_substr($postalCode, 5);

        return $zip.'-'.$plusFour;
    }
}
