<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\MPHandler;

describe('MPHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new MPHandler();

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
        ['12345', null],
        ['96949', null],
        ['96950', '96950'],
        ['96951', '96951'],
        ['96952', '96952'],
        ['96953', null],
        ['123456', null],
        ['1234567', null],
        ['12345678', null],
        ['123456789', null],
        ['969490000', null],
        ['969501234', '96950-1234'],
        ['969529999', '96952-9999'],
        ['969530000', null],
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
        $handler = new MPHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
