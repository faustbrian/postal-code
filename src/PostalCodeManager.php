<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode;

use Cline\PostalCode\Contracts\PostalCodeHandler;
use Cline\PostalCode\Exceptions\InvalidPostalCodeException;
use Cline\PostalCode\Exceptions\UnknownCountryException;
use Illuminate\Container\Attributes\Config;
use Illuminate\Container\Attributes\Singleton;

use function array_key_exists;
use function class_exists;
use function mb_strtoupper;
use function preg_match;
use function str_replace;

/**
 * Manages postal code validation and formatting across multiple countries.
 *
 * Provides a centralized service for validating and formatting postal codes
 * according to country-specific rules. Supports custom handler registration
 * for extending or overriding default country handlers.
 *
 * @author Brian Faust <brian@cline.sh>
 */
#[Singleton()]
final class PostalCodeManager
{
    /**
     * Cached country postal code handlers, indexed by country code.
     *
     * Stores resolved handler instances to avoid repeated lookups and instantiation.
     *
     * @var array<string, null|PostalCodeHandler>
     */
    private array $handlers = [];

    /**
     * Create a new PostalCodeManager instance.
     *
     * @param array<string, class-string<PostalCodeHandler>> $customHandlers Custom handler class names mapped by country code,
     *                                                                       loaded from the configuration file. Allows overriding
     *                                                                       default handlers or adding support for additional countries
     *                                                                       not included in the package by default.
     */
    public function __construct(
        #[Config('postal-code.handlers', [])]
        private array $customHandlers = [],
    ) {}

    /**
     * Creates a fluent PostalCode instance for the given postal code and country.
     *
     * Provides a convenient factory method for creating PostalCode objects
     * with a chainable API for validation and formatting operations.
     *
     * ```php
     * $postal = $manager->for('12345', 'US');
     * echo $postal->format();
     * ```
     *
     * @param  string     $postalCode The postal code to validate and format
     * @param  string     $country    The ISO 3166-1 alpha-2 country code (e.g., 'US', 'CA')
     * @return PostalCode A fluent postal code instance
     */
    public function for(string $postalCode, string $country): PostalCode
    {
        return new PostalCode($this, $postalCode, $country);
    }

    /**
     * Registers a custom handler for a specific country.
     *
     * Allows runtime registration of custom handlers to override default
     * behavior or add support for additional countries. Clears any cached
     * handler for the country to ensure the new handler takes effect.
     *
     * @param  string                          $country      The ISO 3166-1 alpha-2 country code (e.g., 'XX')
     * @param  class-string<PostalCodeHandler> $handlerClass The fully qualified class name of the handler
     * @return self                            Returns this instance for method chaining
     */
    public function registerHandler(string $country, string $handlerClass): self
    {
        $country = mb_strtoupper($country);
        $this->customHandlers[$country] = $handlerClass;

        // Clear cached handler to ensure new handler is used
        unset($this->handlers[$country]);

        return $this;
    }

    /**
     * Validates whether the given postal code is valid for the country.
     *
     * Postal codes are normalized (uppercased, separators removed) before
     * validation. Returns false if the postal code contains invalid characters
     * or doesn't match the country's format requirements.
     *
     * @param string $postalCode The postal code to validate (may include separators)
     * @param string $country    The ISO 3166-1 alpha-2 country code
     *
     * @throws UnknownCountryException If the country code is not supported
     *
     * @return bool True if the postal code is valid, false otherwise
     */
    public function validate(string $postalCode, string $country): bool
    {
        $postalCode = $this->normalizePostalCode($postalCode);
        $handler = $this->getHandler($country);

        if (!$handler instanceof PostalCodeHandler) {
            throw UnknownCountryException::forCountry($country);
        }

        // Reject postal codes containing characters other than letters and digits
        if (preg_match('/^[A-Z0-9]+$/', $postalCode) !== 1) {
            return false;
        }

        return $handler->validate($postalCode);
    }

    /**
     * Formats the postal code according to country-specific standards.
     *
     * Normalizes the input by removing separators and converting to uppercase,
     * then applies country-specific formatting rules. Throws exceptions if the
     * postal code is invalid or contains unsupported characters.
     *
     * @param string $postalCode The postal code to format (may include separators)
     * @param string $country    The ISO 3166-1 alpha-2 country code
     *
     * @throws InvalidPostalCodeException If the postal code is invalid for the country
     * @throws UnknownCountryException    If the country code is not supported
     *
     * @return string The properly formatted postal code
     */
    public function format(string $postalCode, string $country): string
    {
        $postalCode = $this->normalizePostalCode($postalCode);
        $handler = $this->getHandler($country);

        if (!$handler instanceof PostalCodeHandler) {
            throw UnknownCountryException::forCountry($country);
        }

        if (preg_match('/^[A-Z0-9]+$/', $postalCode) !== 1) {
            throw InvalidPostalCodeException::forPostalCode($postalCode, $country, $handler->hint());
        }

        if (!$handler->validate($postalCode)) {
            throw InvalidPostalCodeException::forPostalCode($postalCode, $country, $handler->hint());
        }

        return $handler->format($postalCode);
    }

    /**
     * Formats the postal code or returns null if invalid.
     *
     * A safe alternative to format() that returns null instead of throwing
     * exceptions when the postal code is invalid or contains unsupported characters.
     *
     * @param string $postalCode The postal code to format (may include separators)
     * @param string $country    The ISO 3166-1 alpha-2 country code
     *
     * @throws UnknownCountryException If the country code is not supported
     *
     * @return null|string The formatted postal code, or null if invalid
     */
    public function formatOrNull(string $postalCode, string $country): ?string
    {
        $postalCode = $this->normalizePostalCode($postalCode);
        $handler = $this->getHandler($country);

        if (!$handler instanceof PostalCodeHandler) {
            throw UnknownCountryException::forCountry($country);
        }

        if (preg_match('/^[A-Z0-9]+$/', $postalCode) !== 1) {
            return null;
        }

        if (!$handler->validate($postalCode)) {
            return null;
        }

        return $handler->format($postalCode);
    }

    /**
     * Checks whether a country is supported.
     *
     * Returns true if a handler exists for the country, either as a default
     * handler or a custom registered handler.
     *
     * @param  string $country The ISO 3166-1 alpha-2 country code
     * @return bool   True if the country is supported, false otherwise
     */
    public function isSupportedCountry(string $country): bool
    {
        return $this->getHandler($country) instanceof PostalCodeHandler;
    }

    /**
     * Returns a human-readable hint about the postal code format for a country.
     *
     * Useful for providing user-friendly error messages or input guidance.
     *
     * @param string $country The ISO 3166-1 alpha-2 country code
     *
     * @throws UnknownCountryException If the country code is not supported
     *
     * @return string A description of the expected postal code format
     */
    public function getHint(string $country): string
    {
        $handler = $this->getHandler($country);

        if (!$handler instanceof PostalCodeHandler) {
            throw UnknownCountryException::forCountry($country);
        }

        return $handler->hint();
    }

    /**
     * Normalizes a postal code for validation and formatting.
     *
     * Removes common separators (spaces and hyphens) and converts to uppercase
     * to ensure consistent processing regardless of input format.
     *
     * @param  string $postalCode The postal code to normalize
     * @return string The normalized postal code (uppercase, no separators)
     */
    private function normalizePostalCode(string $postalCode): string
    {
        return mb_strtoupper(str_replace([' ', '-'], '', $postalCode));
    }

    /**
     * Retrieves the handler for a country, using cache when available.
     *
     * @param  string                 $country The ISO 3166-1 alpha-2 country code
     * @return null|PostalCodeHandler The handler instance, or null if the country is not supported
     */
    private function getHandler(string $country): ?PostalCodeHandler
    {
        $country = mb_strtoupper($country);

        if (array_key_exists($country, $this->handlers)) {
            return $this->handlers[$country];
        }

        return $this->handlers[$country] = $this->resolveHandler($country);
    }

    /**
     * Resolves and instantiates the appropriate handler for a country.
     *
     * Checks for custom handlers first, then falls back to default handlers
     * in the Handlers namespace. Returns null if no handler exists.
     *
     * @param  string                 $country The ISO 3166-1 alpha-2 country code
     * @return null|PostalCodeHandler The handler instance, or null if not found
     */
    private function resolveHandler(string $country): ?PostalCodeHandler
    {
        // Validate country code format
        if (preg_match('/^[A-Z]{2}$/', $country) !== 1) {
            return null;
        }

        // Check for custom handler first
        if (array_key_exists($country, $this->customHandlers)) {
            $class = $this->customHandlers[$country];

            return class_exists($class) ? new $class() : null;
        }

        // Fall back to default handler
        /** @var class-string<PostalCodeHandler> $class */
        $class = __NAMESPACE__.'\\Handlers\\'.$country.'Handler';

        return class_exists($class) ? new $class() : null;
    }
}
