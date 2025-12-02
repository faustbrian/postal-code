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
 * Validates and formats postal codes for the United Kingdom.
 *
 * UK postal codes (postcodes) follow a complex structure with six different
 * format patterns. Each postcode consists of an outward code (identifying
 * the postal area and district) and an inward code (identifying the sector
 * and unit). The formats are: A9 9AA, A9A 9AA, A99 9AA, AA9 9AA, AA9A 9AA,
 * and AA99 9AA, where A represents a letter and 9 represents a digit.
 *
 * Only specific letters are permitted at each position, and area codes must
 * match the list of valid UK postal areas. The special postcode "GIR 0AA"
 * (for Girobank) is handled separately.
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postcodes_in_the_United_Kingdom
 * @see https://en.wikipedia.org/wiki/List_of_postcode_areas_in_the_United_Kingdom
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class GBHandler implements PostalCodeHandler
{
    /**
     * List of all valid UK postal area codes.
     *
     * Includes geographic area codes (e.g., AB for Aberdeen, B for Birmingham)
     * and non-geographic codes (BF, BX, XX) used for special purposes like
     * PO boxes and BFPO addresses.
     *
     * @var array<non-empty-string>
     */
    private const array AREA_CODES = [
        'AB', 'AL', 'B', 'BA', 'BB', 'BD', 'BH', 'BL', 'BN', 'BR', 'BS', 'BT', 'CA', 'CB', 'CF', 'CH', 'CM', 'CO', 'CR',
        'CT', 'CV', 'CW', 'DA', 'DD', 'DE', 'DG', 'DH', 'DL', 'DN', 'DT', 'DY', 'E', 'EC', 'EH', 'EN', 'EX', 'FK', 'FY',
        'G', 'GL', 'GU', 'HA', 'HD', 'HG', 'HP', 'HR', 'HS', 'HU', 'HX', 'IG', 'IP', 'IV', 'KA', 'KT', 'KW', 'KY', 'L',
        'LA', 'LD', 'LE', 'LL', 'LN', 'LS', 'LU', 'M', 'ME', 'MK', 'ML', 'N', 'NE', 'NG', 'NN', 'NP', 'NR', 'NW', 'OL',
        'OX', 'PA', 'PE', 'PH', 'PL', 'PO', 'PR', 'RG', 'RH', 'RM', 'S', 'SA', 'SE', 'SG', 'SK', 'SL', 'SM', 'SN', 'SO',
        'SP', 'SR', 'SS', 'ST', 'SW', 'SY', 'TA', 'TD', 'TF', 'TN', 'TQ', 'TR', 'TS', 'TW', 'UB', 'W', 'WA', 'WC', 'WD',
        'WF', 'WN', 'WR', 'WS', 'WV', 'YO', 'ZE',
        // non-geographic
        'BF', 'BX', 'XX',
    ];

    /**
     * Cached regular expression patterns for postcode validation.
     *
     * Lazily built on first use to match the six UK postcode formats.
     * Each pattern includes capturing groups for the outward code, area
     * code, and inward code to enable validation and formatting.
     *
     * @var null|array<non-empty-string>
     */
    private ?array $patterns = null;

    /**
     * Validates whether the provided postal code matches UK format.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid for UK, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats the postal code according to UK standards.
     *
     * Converts unformatted postcodes to the standard format with a space
     * separating the outward and inward codes (e.g., "SW1A1AA" becomes
     * "SW1A 1AA"). Returns the original input unchanged if invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code, or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable description of the postal code format.
     *
     * @return string Description of UK postal code requirements
     */
    public function hint(): string
    {
        return 'PostalCodes can have six different formats: A9 9AA, A9A 9AA, A99 9AA, AA9 9AA, AA9A 9AA, AA99 9AA. A stands for a capital letter, 9 stands for a digit.';
    }

    /**
     * Performs validation and formatting of the postal code.
     *
     * Handles the special case "GIR 0AA" separately, then validates against
     * the six standard UK postcode patterns. Extracts the area code from
     * matched postcodes and verifies it against the list of valid area codes.
     * Returns the formatted postcode with proper spacing if valid.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The formatted postal code, or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        // Handle special case for Girobank postcode
        if ($postalCode === 'GIR0AA') {
            return 'GIR 0AA';
        }

        // Validate against standard UK postcode patterns
        foreach ($this->getPatterns() as $pattern) {
            if (preg_match($pattern, $postalCode, $matches) === 1) {
                [, $outwardCode, $areaCode, $inwardCode] = $matches;

                // Verify area code is in the list of valid UK postal areas
                if (!in_array($areaCode, self::AREA_CODES, true)) {
                    return null;
                }

                return $outwardCode.' '.$inwardCode;
            }
        }

        return null;
    }

    /**
     * Builds and caches regular expression patterns for UK postcode validation.
     *
     * Constructs six patterns matching the UK postcode formats:
     * - A9 9AA    (e.g., M1 1AA)
     * - A9A 9AA   (e.g., M60 1AA)
     * - A99 9AA   (e.g., M99 1AA)
     * - AA9 9AA   (e.g., SW1 1AA)
     * - AA9A 9AA  (e.g., SW1A 1AA)
     * - AA99 9AA  (e.g., SW99 1AA)
     *
     * Each pattern includes three capturing groups:
     * 1. The complete outward code for formatting
     * 2. The area code (letters only) for validation against AREA_CODES
     * 3. The inward code for formatting
     *
     * Only specific letters are permitted at each position based on Royal
     * Mail specifications to avoid ambiguity with similar-looking characters.
     *
     * @return array<non-empty-string> Array of regex patterns for validation
     */
    private function getPatterns(): array
    {
        if ($this->patterns !== null) {
            return $this->patterns;
        }

        $n = '[0-9]';

        // Outward code letter restrictions (based on Royal Mail specifications)
        $alphaOut1 = '[ABCDEFGHIJKLMNOPRSTUWYZ]'; // First position: all letters except Q, V, X
        $alphaOut2 = '[ABCDEFGHKLMNOPQRSTUVWXY]'; // Second position: all letters except I, J, Z
        $alphaOut3 = '[ABCDEFGHJKPSTUW]';          // Third position: limited set for district suffix
        $alphaOut4 = '[ABEHMNPRVWXY]';             // Fourth position: limited set for district suffix

        // Inward code letter restrictions (excludes C, I, K, M, O, V to avoid confusion)
        $alphaIn = '[ABCDEFGHJLNPQRSTUWXYZ]';

        $outPatterns = [];

        // AN format (e.g., M1)
        $outPatterns[] = '('.$alphaOut1.')'.$n;

        // ANA format (e.g., M1A)
        $outPatterns[] = '('.$alphaOut1.')'.$n.$alphaOut3;

        // ANN format (e.g., M99)
        $outPatterns[] = '('.$alphaOut1.')'.$n.$n;

        // AAN format (e.g., SW1)
        $outPatterns[] = '('.$alphaOut1.$alphaOut2.')'.$n;

        // AANA format (e.g., SW1A)
        $outPatterns[] = '('.$alphaOut1.$alphaOut2.')'.$n.$alphaOut4;

        // AANN format (e.g., SW99)
        $outPatterns[] = '('.$alphaOut1.$alphaOut2.')'.$n.$n;

        // Inward code always follows NAA format (e.g., 1AA)
        $inPattern = $n.$alphaIn.$alphaIn;

        $patterns = [];

        foreach ($outPatterns as $outPattern) {
            $patterns[] = '/^('.$outPattern.')('.$inPattern.')$/';
        }

        return $this->patterns = $patterns;
    }
}
