<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\LCHandler;

describe('LCHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new LCHandler();

        if ($expected === null) {
            expect($handler->validate($input))->toBeFalse();
        } else {
            expect($handler->validate($input))->toBeTrue();
            expect($handler->format($input))->toBe($expected);
        }
    })->with([
        ['LC12345', 'LC12 345'],
        ['LC1234', null],
        ['LX12345', null],
        ['XC12345', null],
        ['XLC12345', null],
        ['LC123456', null],
        ['', null],
        ['1', null],
        ['12', null],
        ['123', null],
        ['1234', null],
        ['12345', null],
        ['123456', null],
        ['1234567', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
        ['ABCDEF', null],
        ['ABCDEFG', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new LCHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
