<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\LBHandler;

describe('LBHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new LBHandler();

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
        ['1234', '1234'], // 4 digits (rural)
        ['12345', '12345'], // 5 digits (placeholder)
        ['123456', null],
        ['1234567', null],
        ['12345678', '1234 5678'], // 8 digits (urban/P.O. Box)
        ['123456789', null],
        ['1000', '1000'], // Beirut region example
        ['00000', '00000'], // Placeholder example
        ['11072810', '1107 2810'], // P.O. Box example
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
        ['ABCDEFG', null],
        ['ABCDEFGH', null],
        ['ABCDEFGHI', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new LBHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
