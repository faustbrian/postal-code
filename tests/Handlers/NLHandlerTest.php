<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\NLHandler;

describe('NLHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new NLHandler();

        if ($expected === null) {
            expect($handler->validate($input))->toBeFalse();
        } else {
            expect($handler->validate($input))->toBeTrue();
            expect($handler->format($input))->toBe($expected);
        }
    })->with([
        ['', null],
        ['0123AB', null],
        ['1234AB', '1234 AB'],
        ['1234SS', null],
        ['1234SD', null],
        ['1234SA', null],
        ['12345AB', null],
        ['123AB', null],
        ['123ABC', null],
        ['1234ABC', null],
        ['1234AB1', null],
        ['1234ABC', null],
        ['1', null],
        ['12', null],
        ['123', null],
        ['1234', null],
        ['12345', null],
        ['123456', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new NLHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
