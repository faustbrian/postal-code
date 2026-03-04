<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\NCHandler;

describe('NCHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new NCHandler();

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
        ['98799', null],
        ['98800', '98800'],
        ['98801', '98801'],
        ['98889', '98889'],
        ['98890', '98890'],
        ['98891', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new NCHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
