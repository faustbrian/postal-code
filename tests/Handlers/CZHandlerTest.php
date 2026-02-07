<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\CZHandler;

describe('CZHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new CZHandler();

        if ($expected === null) {
            expect($handler->validate($input))->toBeFalse();
        } else {
            expect($handler->validate($input))->toBeTrue();
            expect($handler->format($input))->toBe($expected);
        }
    })->with([
        ['', null],
        ['1', null],
        ['12', null],
        ['123', null],
        ['01234', null],
        ['12345', '123 45'],
        ['79999', '799 99'],
        ['80000', null],
        ['99999', null],
        ['01234', null],
        ['81000', null],
        ['123456', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new CZHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
