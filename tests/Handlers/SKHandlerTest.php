<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\SKHandler;

describe('SKHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new SKHandler();

        if ($expected === null) {
            expect($handler->validate($input))->toBeFalse();
        } else {
            expect($handler->validate($input))->toBeTrue();
            expect($handler->format($input))->toBe($expected);
        }
    })->with([
        ['', null],
        ['0', null],
        ['01', null],
        ['012', null],
        ['0123', null],
        ['01234', '012 34'],
        ['12345', null],
        ['79999', null],
        ['80000', '800 00'],
        ['99999', '999 99'],
        ['12345', null],
        ['60200', null],
        ['012345', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new SKHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
