<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\BMHandler;

describe('BMHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new BMHandler();

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
        ['A', null],
        ['A1', null],
        ['A12', null],
        ['A123', null],
        ['AB', null],
        ['AB1', null],
        ['AB12', 'AB 12'],
        ['AB123', null],
        ['ABC', null],
        ['ABC1', null],
        ['ABC12', null],
        ['ABCD', 'AB CD'],
        ['ABCDE', null],
        ['ABCD1', null],
        ['1ABC', null],
        ['1ABCD', null],
        ['1AB23', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new BMHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
