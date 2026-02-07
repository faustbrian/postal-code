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
 * Exception thrown when a postal code fails validation for a specific country.
 *
 * This exception is raised when attempting to format or validate a postal code
 * that does not match the expected format rules for the specified country. The
 * exception captures the invalid postal code, the country code, and optionally
 * a hint about the expected format to assist with error handling and user feedback.
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class InvalidPostalCodeException extends Exception implements PostalCodeException
{
    /**
     * Create a new invalid postal code exception.
     *
     * @param string      $postalCode The invalid postal code that failed validation
     * @param string      $country    The ISO 3166-1 alpha-2 country code for which validation failed
     * @param null|string $hint       Optional human-readable hint about the expected postal code format
     *                                for the country, used to provide actionable error messages to users
     * @param string      $message    The exception message displayed when the exception is thrown
     */
    private function __construct(
        private readonly string $postalCode,
        private readonly string $country,
        private readonly ?string $hint,
        string $message,
    ) {
        parent::__construct($message);
    }

    /**
     * Creates an exception for a postal code that failed validation.
     *
     * Builds a descriptive error message that includes the invalid postal code
     * and optionally appends a format hint to guide the user toward the correct
     * postal code structure for the specified country.
     *
     * @param  string      $postalCode The invalid postal code that failed validation
     * @param  string      $country    The ISO 3166-1 alpha-2 country code for which validation failed
     * @param  null|string $hint       Optional hint about the expected format to include in the error message
     * @return self        A new exception instance with contextual information about the validation failure
     */
    public static function forPostalCode(string $postalCode, string $country, ?string $hint = null): self
    {
        $message = 'Invalid postalCode: '.$postalCode;

        if ($hint !== null) {
            $message .= '. '.$hint;
        }

        return new self($postalCode, $country, $hint, $message);
    }

    /**
     * Returns the invalid postal code that caused the exception.
     *
     * @return string The postal code that failed validation
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * Returns the country code for which validation failed.
     *
     * @return string The ISO 3166-1 alpha-2 country code (e.g., "US", "CA", "GB")
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Returns the format hint if one was provided.
     *
     * The hint describes the expected postal code format for the country and
     * can be displayed to users to help them correct their input.
     *
     * @return null|string The format hint, or null if no hint was provided
     */
    public function getHint(): ?string
    {
        return $this->hint;
    }
}
