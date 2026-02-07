<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\USHandler;

describe('USHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new USHandler();

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
        ['1234', null],
        ['12345', '12345'],
        ['123456', null],
        ['1234567', null],
        ['12345678', null],
        ['123456789', '12345-6789'],
        ['1234567890', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
        ['ABCDEFG', null],
        ['ABCDEFGH', null],
        ['ABCDEFGHI', null],
        ['ABCDEFGHIJK', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new USHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
