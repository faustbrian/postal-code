<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\LTHandler;

describe('LTHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new LTHandler();

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
        ['AB12345', null],
        ['LT12345', 'LT-12345'],
        ['LX12345', null],
        ['XT12345', null],
        ['XLT12345', null],
        ['LTX12345', null],
        ['LTX1234', null],
        ['LT1234', null],
        ['LT123456', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new LTHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
