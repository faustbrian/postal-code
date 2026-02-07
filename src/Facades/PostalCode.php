<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Facades;

use Cline\PostalCode\Contracts\PostalCodeHandler;
use Cline\PostalCode\PostalCode as PostalCodeValue;
use Cline\PostalCode\PostalCodeManager;
use Illuminate\Support\Facades\Facade;
use Override;

/**
 * Laravel facade for the PostalCode validation and formatting service.
 *
 * This facade provides convenient static access to the PostalCodeManager instance,
 * allowing for easy postal code validation, formatting, and country support checks
 * throughout a Laravel application without manual dependency injection.
 *
 * @method static PostalCodeValue   for(string $postalCode, string $country)                                        Creates a validated PostalCode value object for the specified postal code and country
 * @method static string            format(string $postalCode, string $country)                                     Formats a postal code according to the country's conventions
 * @method static string|null       formatOrNull(string $postalCode, string $country)                               Formats a postal code or returns null if invalid
 * @method static string            getHint(string $country)                                                        Returns the format hint for a specific country's postal codes
 * @method static bool              isSupportedCountry(string $country)                                             Checks if a country code has a registered postal code handler
 * @method static PostalCodeManager registerHandler(string $country, class-string<PostalCodeHandler> $handlerClass) Registers a custom postal code handler for a country
 * @method static bool              validate(string $postalCode, string $country)                                   Validates a postal code against the country's format rules
 *
 * @author Brian Faust <brian@cline.sh>
 * @see PostalCodeManager
 */
final class PostalCode extends Facade
{
    /**
     * Returns the service container binding key for the facade.
     *
     * This method tells Laravel's facade system which service to resolve
     * from the container when static methods are called on this facade.
     *
     * @return string The fully qualified class name of the PostalCodeManager service
     */
    #[Override()]
    protected static function getFacadeAccessor(): string
    {
        return PostalCodeManager::class;
    }
}
