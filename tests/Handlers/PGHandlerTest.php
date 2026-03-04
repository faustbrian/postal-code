<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\PGHandler;

describe('PGHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new PGHandler();

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
        ['123', '123'],
        ['1234', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new PGHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
