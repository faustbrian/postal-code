<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\FKHandler;

describe('FKHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new FKHandler();

        if ($expected === null) {
            expect($handler->validate($input))->toBeFalse();
        } else {
            expect($handler->validate($input))->toBeTrue();
            expect($handler->format($input))->toBe($expected);
        }
    })->with([
        ['', null],
        ['1234567', null],
        ['FIQQ1ZX', null],
        ['FIQQ1ZZ', 'FIQQ 1ZZ'],
        ['AIQQ1ZZ', null],
        ['FIQQ1ZZA', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new FKHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
