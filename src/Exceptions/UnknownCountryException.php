<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Exceptions;

use Cline\PostalCode\Contracts\PostalCodeException;
use Exception;

/**
 * Exception thrown when an unsupported or unrecognized country code is provided.
 *
 * This exception is raised when attempting to validate or format a postal code
 * for a country that either does not have a registered handler or uses an invalid
 * ISO 3166-1 alpha-2 country code. The exception captures the problematic country
 * code to assist with debugging and error reporting.
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class UnknownCountryException extends Exception implements PostalCodeException
{
    /**
     * Create a new unknown country exception.
     *
     * @param string $country The ISO 3166-1 alpha-2 country code that was not recognized or is not supported
     *                        by the postal code validation system. This code will be included in error
     *                        messages to help identify which country lookup failed.
     * @param string $message The exception message displayed when the exception is thrown
     */
    private function __construct(
        private readonly string $country,
        string $message,
    ) {
        parent::__construct($message);
    }

    /**
     * Creates an exception for an unsupported or invalid country code.
     *
     * Builds a descriptive error message that identifies which country code
     * was not recognized, helping developers understand why postal code
     * validation or formatting failed.
     *
     * @param  string $country The ISO 3166-1 alpha-2 country code that is not supported
     * @return self   A new exception instance with information about the unknown country
     */
    public static function forCountry(string $country): self
    {
        return new self($country, 'Unknown country: '.$country);
    }

    /**
     * Returns the unsupported country code that caused the exception.
     *
     * @return string The ISO 3166-1 alpha-2 country code (e.g., "ZZ", "XX")
     */
    public function getCountry(): string
    {
        return $this->country;
    }
}
