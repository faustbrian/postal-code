<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\ILHandler;

describe('ILHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new ILHandler();

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
        ['12345', null],
        ['123456', null],
        ['1234567', '1234567'],
        ['123456X', null],
        ['X123456', null],
        ['12345678', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
        ['ABCDEFG', null],
        ['ABCDEFH', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new ILHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
