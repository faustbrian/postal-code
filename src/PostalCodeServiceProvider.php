<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode;

use Override;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Laravel service provider for the postal code validation package.
 *
 * Registers the package configuration and bootstraps the postal code
 * validation services into the Laravel application container.
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class PostalCodeServiceProvider extends PackageServiceProvider
{
    /**
     * Configures the postal code package for Laravel integration.
     *
     * Sets up the package name and registers the configuration file,
     * making it publishable for customization in Laravel applications.
     *
     * @param Package $package The package instance to configure
     */
    #[Override()]
    public function configurePackage(Package $package): void
    {
        $package
            ->name('postal-code')
            ->hasConfigFile();
    }
}
