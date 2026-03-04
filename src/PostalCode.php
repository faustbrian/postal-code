<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode;

use Cline\PostalCode\Exceptions\InvalidPostalCodeException;
use Cline\PostalCode\Exceptions\UnknownCountryException;
use Stringable;

use function mb_strtoupper;
use function once;
use function str_replace;

/**
 * Fluent interface for postal code validation and formatting.
 *
 * Provides a chainable API for working with postal codes, offering validation,
 * formatting, and utility methods. The instance automatically normalizes postal
 * codes by removing separators and converting to uppercase for consistent processing.
 *
 * ```php
 * $postalCode = PostalCode::for('12345-6789', 'US');
 * if ($postalCode->isValid()) {
 *     echo $postalCode->format(); // "12345-6789"
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 *
 * @psalm-immutable
 */
final readonly class PostalCode implements Stringable
{
    /**
     * The normalized postal code (uppercase, no separators).
     */
    private string $normalizedPostalCode;

    /**
     * Create a new PostalCode instance.
     *
     * @param PostalCodeManager $manager    The postal code manager instance for validation and formatting
     * @param string            $postalCode The postal code to validate and format (may include separators)
     * @param string            $country    The ISO 3166-1 alpha-2 country code (e.g., 'US', 'GB')
     */
    public function __construct(
        private PostalCodeManager $manager,
        private string $postalCode,
        private string $country,
    ) {
        $this->normalizedPostalCode = mb_strtoupper(str_replace([' ', '-'], '', $postalCode));
    }

    /**
     * Returns the string representation of the postal code.
     *
     * Automatically attempts to format the postal code. If formatting fails,
     * returns the normalized postal code instead.
     *
     * @return string The formatted postal code if valid, otherwise the normalized postal code
     */
    public function __toString(): string
    {
        return $this->formatOrNull() ?? $this->normalizedPostalCode;
    }

    /**
     * Validates whether the postal code is valid for the specified country.
     *
     * This method memoizes the result using Laravel's `once()` helper for
     * performance when called multiple times on the same instance.
     *
     * @throws UnknownCountryException If the country code is not supported
     *
     * @return bool True if the postal code is valid, false otherwise
     */
    public function isValid(): bool
    {
        return once(fn (): bool => $this->manager->validate($this->normalizedPostalCode, $this->country));
    }

    /**
     * Returns the properly formatted postal code.
     *
     * Throws an exception if the postal code is invalid. This method memoizes
     * the result for performance when called multiple times.
     *
     * @throws InvalidPostalCodeException If the postal code is invalid
     * @throws UnknownCountryException    If the country code is not supported
     *
     * @return string The formatted postal code
     */
    public function format(): string
    {
        return once(fn (): string => $this->manager->format($this->normalizedPostalCode, $this->country));
    }

    /**
     * Returns the formatted postal code or null if invalid.
     *
     * This is a safe alternative to format() that returns null instead of
     * throwing an exception when the postal code is invalid. Memoizes the result.
     *
     * @throws UnknownCountryException If the country code is not supported
     *
     * @return null|string The formatted postal code, or null if invalid
     */
    public function formatOrNull(): ?string
    {
        return once(fn (): ?string => $this->manager->formatOrNull($this->normalizedPostalCode, $this->country));
    }

    /**
     * Returns the formatted postal code or a default value if invalid.
     *
     * Provides a fallback value when the postal code cannot be formatted.
     *
     * @param string $default The default value to return if the postal code is invalid
     *
     * @throws UnknownCountryException If the country code is not supported
     *
     * @return string The formatted postal code, or the default value if invalid
     */
    public function formatOr(string $default): string
    {
        return $this->formatOrNull() ?? $default;
    }

    /**
     * Returns a human-readable hint about the expected format for the country.
     *
     * Provides guidance on what format the postal code should have, useful
     * for displaying validation errors or input hints to users.
     *
     * @throws UnknownCountryException If the country code is not supported
     *
     * @return string A description of the expected postal code format
     */
    public function hint(): string
    {
        return $this->manager->getHint($this->country);
    }

    /**
     * Returns the original, unmodified postal code value.
     *
     * Preserves the exact input provided during construction, including
     * any spaces, hyphens, or case variations.
     *
     * @return string The original postal code exactly as provided
     */
    public function original(): string
    {
        return $this->postalCode;
    }

    /**
     * Returns the normalized postal code.
     *
     * The normalized form is uppercase with all spaces and hyphens removed,
     * making it suitable for consistent comparison and storage.
     *
     * @return string The normalized postal code (uppercase, no separators)
     */
    public function normalized(): string
    {
        return $this->normalizedPostalCode;
    }

    /**
     * Returns the country code associated with this postal code.
     *
     * @return string The ISO 3166-1 alpha-2 country code
     */
    public function country(): string
    {
        return $this->country;
    }

    /**
     * Checks whether the specified country is supported.
     *
     * Useful for determining if validation and formatting are available
     * for the country before attempting operations.
     *
     * @return bool True if the country is supported, false otherwise
     */
    public function isCountrySupported(): bool
    {
        return $this->manager->isSupportedCountry($this->country);
    }
}
