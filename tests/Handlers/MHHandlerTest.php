<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\MHHandler;

describe('MHHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new MHHandler();

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
        ['96959', null],
        ['96960', '96960'],
        ['96965', '96965'],
        ['96970', '96970'],
        ['96971', null],
        ['123456', null],
        ['1234567', null],
        ['12345678', null],
        ['123456789', null],
        ['969590000', null],
        ['969601234', '96960-1234'],
        ['969709999', '96970-9999'],
        ['969710000', null],
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
        $handler = new MHHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
