<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\BRHandler;

describe('BRHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new BRHandler();

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
        ['1234567', null],
        ['12345678', '12345-678'],
        ['123456789', null],
        ['ABCDEFGH', null],
        ['1234567A', null],
        ['A1234567', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new BRHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
