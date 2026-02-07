<?php declare(strict_types=1);

/**
 * Copyright (c) 2025 Cline. All Rights Reserved.
 *
 * This software is proprietary and confidential. Unauthorized copying,
 * modification, distribution, or use of this software, via any medium,
 * is strictly prohibited without prior written permission.
 *
 * For licensing inquiries, contact: legal@cline.sh
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Custom PostalCode Handlers
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom handlers for specific countries. This allows
    | you to override the default handlers or add support for countries that
    | are not included in the package.
    |
    | Each key should be an ISO 3166-1 alpha-2 country code (e.g., 'US', 'GB'),
    | and the value should be a fully qualified class name that implements
    | \Cline\PostalCode\Contracts\PostalCodeHandler.
    |
    | Example:
    | 'handlers' => [
    |     'DE' => \App\PostalCode\CustomDEHandler::class,
    |     'XX' => \App\PostalCode\CustomCountryHandler::class,
    | ],
    |
    */

    'handlers' => [
        // 'DE' => \App\PostalCode\CustomDEHandler::class,
    ],
];
