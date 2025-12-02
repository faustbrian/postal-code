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
 * Postal code handler for the Republic of Ireland (IE).
 *
 * Ireland uses the Eircode system, consisting of a Routing Key (3 characters)
 * and a Unique Identifier (4 characters), separated by a space. The system
 * supports 139 different Routing Keys with specific character constraints.
 *
 * Valid character set for letters: A, C, D, E, F, H, K, N, P, R, T, V, W, X, Y
 * (excluding B, G, I, J, L, M, O, Q, S, U, Z to avoid confusion)
 *
 * @see https://en.wikipedia.org/wiki/List_of_postal_codes
 * @see https://en.wikipedia.org/wiki/Postal_addresses_in_the_Republic_of_Ireland
 * @see https://www.eircode.ie/docs/default-source/Common/prepareyourbusinessforeircode-edition3published.pdf
 * @see https://www.autoaddress.ie/support/developer-centre/resources/routing-key-boundaries
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class IEHandler implements PostalCodeHandler
{
    /**
     * Regular expression pattern for validating Eircode format.
     *
     * The pattern validates the Routing Key (first part) against all 139 valid
     * routing keys and ensures the Unique Identifier (second part) contains
     * only allowed characters (A, C, D, E, F, H, K, N, P, R, T, V, W, X, Y, 0-9).
     */
    private const string PATTERN = '/^'
        .'('
        .'A41|A42|A45|A63|A67|A75|A81|A82|A83|A84|A85|A86|A91|A92|A94|A96|A98|C15|D01|D02|D03|D04|D05|D06|D6W|D07|D08|D09|'
        .'D10|D11|D12|D13|D14|D15|D16|D17|D18|D20|D22|D24|E21|E25|E32|E34|E41|E45|E53|E91|F12|F23|F26|F28|F31|F35|F42|F45|'
        .'F52|F56|F91|F92|F93|F94|H12|H14|H16|H18|H23|H53|H54|H62|H65|H71|H91|K32|K34|K36|K45|K56|K67|K78|N37|N39|N41|N91|'
        .'P12|P14|P17|P24|P25|P31|P32|P36|P43|P47|P51|P56|P61|P67|P72|P75|P81|P85|R14|R21|R32|R35|R42|R45|R51|R56|R93|R95|'
        .'T12|T23|T34|T45|T56|V14|V15|V23|V31|V35|V42|V92|V93|V94|V95|W12|W23|W34|W91|X35|X42|X91|Y14|Y21|Y25|Y34|Y35'
        .')'
        .'([ACDEFHKNPRTVWXY0-9]{4})'
        .'$/';

    /**
     * Validates an Irish Eircode postal code.
     *
     * Postal codes are valid if they match one of the 139 valid Routing Keys
     * followed by a 4-character Unique Identifier using the allowed character set.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode): bool
    {
        return $this->doFormat($postalCode) !== null;
    }

    /**
     * Formats an Irish Eircode to its standard representation.
     *
     * Ensures proper spacing between the Routing Key and Unique Identifier.
     * Returns the original input unchanged if the postal code is invalid.
     *
     * @param  string $postalCode The postal code to format
     * @return string The formatted postal code (with space) or original input if invalid
     */
    public function format(string $postalCode): string
    {
        return $this->doFormat($postalCode) ?? $postalCode;
    }

    /**
     * Returns a human-readable hint describing the postal code format.
     *
     * @return string Description of the postal code format requirements
     */
    public function hint(): string
    {
        return 'PostalCodes can have at least the following thirteen different formats:';
    }

    /**
     * Validates and formats the postal code.
     *
     * Verifies the postal code against the complete list of valid Routing Keys
     * and the allowed character set for the Unique Identifier. Returns the
     * formatted code with proper spacing or null if invalid.
     *
     * @param  string      $postalCode The postal code to validate and format
     * @return null|string The validated postal code with space separator or null if invalid
     */
    private function doFormat(string $postalCode): ?string
    {
        if (preg_match(self::PATTERN, $postalCode, $matches) !== 1) {
            return null;
        }

        return $matches[1].' '.$matches[2];
    }
}
