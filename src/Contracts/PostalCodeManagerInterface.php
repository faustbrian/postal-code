<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Contracts;

use Cline\PostalCode\Exceptions\InvalidPostalCodeException;
use Cline\PostalCode\Exceptions\UnknownCountryException;
use Cline\PostalCode\PostalCode;

/**
 * Contract for postal code validation and formatting operations.
 *
 * Provides a mockable abstraction for consumers and facades while allowing
 * the concrete manager implementation to remain final.
 */
interface PostalCodeManagerInterface
{
    public function for(string $postalCode, string $country): PostalCode;

    /**
     * @throws UnknownCountryException
     */
    public function validate(string $postalCode, string $country): bool;

    /**
     * @throws InvalidPostalCodeException
     * @throws UnknownCountryException
     */
    public function format(string $postalCode, string $country): string;

    /**
     * @throws UnknownCountryException
     */
    public function formatOrNull(string $postalCode, string $country): ?string;

    public function isSupportedCountry(string $country): bool;

    /**
     * @throws UnknownCountryException
     */
    public function getHint(string $country): string;

    /**
     * @param class-string<PostalCodeHandler> $handlerClass
     */
    public function registerHandler(string $country, string $handlerClass): self;
}
