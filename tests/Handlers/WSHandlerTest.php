<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\WSHandler;

describe('WSHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new WSHandler();

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
        ['1234', 'WS1234'],
        ['12345', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['WS', null],
        ['WS1', null],
        ['WS123', null],
        ['WS1234', 'WS1234'],
        ['WX1234', null],
        ['XS1234', null],
        ['WS12345', null],
        ['XWS1234', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new WSHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
