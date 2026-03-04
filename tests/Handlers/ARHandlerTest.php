<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\ARHandler;

describe('ARHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new ARHandler();

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
        ['1234', '1234'],
        ['12345', null],
        ['123456', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
        ['A1234', null],
        ['A1234B', null],
        ['A1234BC', null],
        ['A1234BCD', 'A1234BCD'],
        ['A1234BCDE', null],
        ['A12345BC', null],
        ['A12345BCD', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new ARHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
