<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Support;

use function mb_strlen;
use function mb_substr;

/**
 * Removes country code prefixes from postal codes for validation compatibility.
 *
 * In certain countries like Belgium (B-) and Luxembourg (L-), it is common practice
 * for citizens and organizations to prefix postal codes with country identifiers,
 * despite this not being part of the official format specification. Rather than
 * rejecting these commonly-used variants, this trait provides graceful handling by
 * stripping the prefix before validation, ensuring user-friendly acceptance while
 * maintaining format correctness in the output.
 *
 * ```php
 * // Belgian postal code with country prefix
 * $handler->stripPrefix('B-1000', 'B-'); // Returns: '1000'
 * $handler->stripPrefix('1000', 'B-');   // Returns: '1000' (unchanged)
 * ```
 *
 * @internal This trait is intended for use within country-specific postal code handlers
 *
 * @see https://en.wikipedia.org/wiki/Postal_codes_in_Belgium
 *
 * @author Brian Faust <brian@cline.sh>
 */
trait StripPrefix
{
    /**
     * Removes a country code prefix from the postal code if present.
     *
     * Performs a case-sensitive multibyte-safe prefix check and removal. If the
     * postal code begins with the specified prefix, it is stripped and the
     * remaining string is returned. Otherwise, the original postal code is
     * returned unchanged.
     *
     * @param  string $postalCode The postal code that may contain a country prefix (e.g., 'B-1000')
     * @param  string $prefix     The country code prefix to remove (e.g., 'B-' for Belgium, 'L-' for Luxembourg)
     * @return string The postal code with the prefix removed if it was present, or the original postal code
     */
    public function stripPrefix(string $postalCode, string $prefix): string
    {
        $prefixLength = mb_strlen($prefix);

        // Check if postal code starts with the country prefix
        if (mb_substr($postalCode, 0, $prefixLength) === $prefix) {
            return mb_substr($postalCode, $prefixLength);
        }

        return $postalCode;
    }
}
