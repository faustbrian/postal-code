<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\GUHandler;

describe('GUHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new GUHandler();

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
        ['96909', null],
        ['96910', '96910'],
        ['96932', '96932'],
        ['96933', null],
        ['123456', null],
        ['1234567', null],
        ['12345678', null],
        ['123456789', null],
        ['969090000', null],
        ['969101234', '96910-1234'],
        ['969329999', '96932-9999'],
        ['969330000', null],
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
        $handler = new GUHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
