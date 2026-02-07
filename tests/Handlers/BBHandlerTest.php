<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\BBHandler;

describe('BBHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new BBHandler();

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
        ['12345', 'BB12345'],
        ['123456', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
        ['BB', null],
        ['BB1', null],
        ['BB123', null],
        ['BB1234', null],
        ['BB11000', 'BB11000'],
        ['BB110000', null],
        ['XBB11000', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new BBHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
