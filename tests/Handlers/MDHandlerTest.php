<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\MDHandler;

describe('MDHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new MDHandler();

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
        ['1234', 'MD-1234'],
        ['12345', null],
        ['123456', null],
        ['1234567', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
        ['ABCDEFG', null],
        ['MD', null],
        ['MD1', null],
        ['MD12', null],
        ['MD123', null],
        ['MD1234', 'MD-1234'],
        ['MD12345', null],
        ['XMD1234', null],
        ['XD1234', null],
        ['MX1234', null],
        ['MDX123', null],
        ['M1234', null],
        ['D1234', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new MDHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
