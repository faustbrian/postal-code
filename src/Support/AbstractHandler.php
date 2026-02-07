<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Support;

use Cline\PostalCode\Contracts\PostalCodeHandler;

/**
 * Abstract base class for postal code handlers.
 *
 * Provides a standardized foundation for country-specific postal code validation
 * and formatting implementations. Includes a default format() method that returns
 * input unchanged, which can be overridden by handlers that require transformation
 * (e.g., adding spaces, hyphens, or case normalization).
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class AbstractHandler implements PostalCodeHandler
{
    /**
     * Format the postal code according to country-specific conventions.
     *
     * Default implementation returns the postal code unchanged. Country-specific
     * handlers should override this method when formatting is required (e.g.,
     * Canadian postal codes add a space, UK postal codes normalize case).
     *
     * @param  string $postalCode The postal code to format (assumed to be valid)
     * @return string The formatted postal code
     */
    public function format(string $postalCode): string
    {
        return $postalCode;
    }

    /**
     * Validate whether the postal code matches country-specific format rules.
     *
     * Each country handler must implement this method to verify the postal code
     * conforms to the country's official format specification using pattern matching,
     * length checks, and any additional validation rules specific to that country.
     *
     * @param  string $postalCode The postal code to validate
     * @return bool   True if the postal code is valid for this country, false otherwise
     */
    abstract public function validate(string $postalCode): bool;

    /**
     * Returns a human-readable description of the expected postal code format.
     *
     * Provides guidance to users about the correct format for this country's postal
     * codes. Should include pattern examples and any special requirements (e.g.,
     * "Five digits (12345)" or "Letter-number-letter space number-letter-number (A1A 1A1)").
     *
     * @return string A descriptive hint about the expected postal code format
     */
    abstract public function hint(): string;
}
