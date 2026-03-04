<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Contracts;

/**
 * Validates and formats postal codes for a specific country.
 *
 * Implementations of this interface provide country-specific validation rules
 * and formatting logic for postal codes. Each handler is responsible for a
 * single country and defines the structure, patterns, and display format for
 * that country's postal codes.
 *
 * All postal code input is expected to be normalized before validation: uppercase
 * alphanumeric characters only, with spaces and dashes already removed by the caller.
 *
 * If the implementation defines a constructor, it must not take any parameters,
 * as handlers are instantiated automatically by the manager without arguments.
 *
 * @author Brian Faust <brian@cline.sh>
 */
interface PostalCodeHandler
{
    /**
     * Validates the given postal code against country-specific rules.
     *
     * The postal code must be a non-empty string of uppercase alphanumeric
     * characters with no separators (spaces and dashes should be stripped
     * by the caller before invoking this method).
     *
     * @param  string $postalCode The normalized postal code to validate
     * @return bool   True if the postal code matches the country's format rules, false otherwise
     */
    public function validate(string $postalCode): bool;

    /**
     * Formats the given postal code according to country-specific conventions.
     *
     * The postal code must be a non-empty string of uppercase alphanumeric
     * characters with no separators. This method assumes the postal code has
     * already been validated and transforms it into the standard display format
     * for the country (e.g., adding prefix, inserting spaces or dashes).
     *
     * @param  string $postalCode The normalized postal code to format
     * @return string The formatted postal code according to country conventions
     */
    public function format(string $postalCode): string;

    /**
     * Returns a hint about the expected postal code format for this country.
     *
     * The hint provides human-readable guidance about the expected structure,
     * such as length, character types, and separators. This is useful for
     * displaying validation error messages or input field placeholders.
     *
     * @return string A concise, human-readable description of the expected format
     */
    public function hint(): string;
}
