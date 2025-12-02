<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\TCHandler;

describe('TCHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new TCHandler();

        if ($expected === null) {
            expect($handler->validate($input))->toBeFalse();
        } else {
            expect($handler->validate($input))->toBeTrue();
            expect($handler->format($input))->toBe($expected);
        }
    })->with([
        ['', null],
        ['1234567', null],
        ['TKCA0ZZ', null],
        ['TKCA1ZZ', 'TKCA 1ZZ'],
        ['TKCA1AB', null],
        ['ATKCA1ZZ', null],
        ['TKCA1ZZA', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new TCHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
