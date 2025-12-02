<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\YTHandler;

describe('YTHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new YTHandler();

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
        ['97599', null],
        ['97600', '97600'],
        ['97601', '97601'],
        ['97689', '97689'],
        ['97690', '97690'],
        ['97691', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new YTHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
