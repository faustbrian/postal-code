<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\GIHandler;

describe('GIHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new GIHandler();

        if ($expected === null) {
            expect($handler->validate($input))->toBeFalse();
        } else {
            expect($handler->validate($input))->toBeTrue();
            expect($handler->format($input))->toBe($expected);
        }
    })->with([
        ['', null],
        ['1234567', null],
        ['GX110AA', null],
        ['GX111AA', 'GX11 1AA'],
        ['GX111AB', null],
        ['AX111AA', null],
        ['GX111AAA', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new GIHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
