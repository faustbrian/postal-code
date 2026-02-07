<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Support;

use function in_array;
use function mb_strtoupper;

/**
 * Provides country-level postal code information.
 *
 * Contains knowledge about which countries have postal code systems and
 * provides fallback values for countries that do not use postal codes.
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class Country
{
    /**
     * ISO 3166-1 alpha-2 country codes for countries without postal code systems.
     *
     * These countries either never implemented a postal code system or have
     * discontinued their system. When shipping to these countries, carriers
     * typically accept a placeholder value.
     */
    private const array WITHOUT_POSTAL_CODES = [
        'AE', // United Arab Emirates
        'AG', // Antigua and Barbuda
        'AN', // Netherlands Antilles (former)
        'AO', // Angola
        'AW', // Aruba
        'BF', // Burkina Faso
        'BI', // Burundi
        'BJ', // Benin
        'BO', // Bolivia
        'BS', // Bahamas
        'BW', // Botswana
        'BZ', // Belize
        'CD', // Democratic Republic of the Congo
        'CF', // Central African Republic
        'CG', // Republic of the Congo
        'CI', // Ivory Coast
        'CK', // Cook Islands
        'CM', // Cameroon
        'CW', // Curacao
        'DJ', // Djibouti
        'DM', // Dominica
        'ER', // Eritrea
        'FJ', // Fiji
        'GA', // Gabon
        'GD', // Grenada
        'GH', // Ghana
        'GM', // Gambia
        'GQ', // Equatorial Guinea
        'GY', // Guyana
        'HK', // Hong Kong
        'JM', // Jamaica
        'KI', // Kiribati
        'KM', // Comoros
        'KN', // Saint Kitts and Nevis
        'KP', // North Korea
        'ML', // Mali
        'MO', // Macau
        'MR', // Mauritania
        'MW', // Malawi
        'NR', // Nauru
        'NU', // Niue
        'QA', // Qatar
        'RW', // Rwanda
        'SB', // Solomon Islands
        'SC', // Seychelles
        'SL', // Sierra Leone
        'SO', // Somalia
        'SR', // Suriname
        'SS', // South Sudan
        'ST', // Sao Tome and Principe
        'SX', // Sint Maarten
        'SY', // Syria
        'TD', // Chad
        'TG', // Togo
        'TK', // Tokelau
        'TL', // Timor-Leste
        'TO', // Tonga
        'TV', // Tuvalu
        'UG', // Uganda
        'VU', // Vanuatu
        'YE', // Yemen
        'ZW', // Zimbabwe
    ];

    /**
     * Determines whether a country uses postal codes.
     *
     * @param  string $country The ISO 3166-1 alpha-2 country code (e.g., 'US', 'HK')
     * @return bool   True if the country uses postal codes, false otherwise
     */
    public static function hasPostalCode(string $country): bool
    {
        return !in_array(mb_strtoupper($country), self::WITHOUT_POSTAL_CODES, true);
    }

    /**
     * Returns a fallback postal code for countries without postal code systems.
     *
     * This value is commonly accepted by shipping carriers and address
     * validation systems when a postal code is required but the destination
     * country does not use postal codes.
     *
     * @return string The fallback postal code ('00000')
     */
    public static function fallbackPostalCode(): string
    {
        return '00000';
    }

    /**
     * Returns all country codes that do not use postal codes.
     *
     * @return list<string> Array of ISO 3166-1 alpha-2 country codes
     */
    public static function countriesWithoutPostalCodes(): array
    {
        return self::WITHOUT_POSTAL_CODES;
    }
}
