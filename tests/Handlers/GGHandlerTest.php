<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\GGHandler;

describe('GGHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new GGHandler();

        if ($expected === null) {
            expect($handler->validate($input))->toBeFalse();
        } else {
            expect($handler->validate($input))->toBeTrue();
            expect($handler->format($input))->toBe($expected);
        }
    })->with([
        ['', null],
        ['AA999AA', null],
        ['XY999AA', null],
        ['GX999AA', null],
        ['GY999AA', 'GY99 9AA'],
        ['GY123AB', 'GY12 3AB'],
        ['XGY123AB', null],
        ['GY123ABC', null],
        ['GY9999A', null],
        ['GY999A9', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new GGHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
