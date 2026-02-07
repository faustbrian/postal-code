<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\SHHandler;

describe('SHHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new SHHandler();

        if ($expected === null) {
            expect($handler->validate($input))->toBeFalse();
        } else {
            expect($handler->validate($input))->toBeTrue();
            expect($handler->format($input))->toBe($expected);
        }
    })->with([
        ['', null],
        ['1234567', null],
        ['STHL1ZX', null],
        ['STHL1ZZ', 'STHL 1ZZ'],
        ['ASTHL1ZZ', null],
        ['STHL1ZZA', null],
        ['ASCN1ZX', null],
        ['ASCN1ZZ', 'ASCN 1ZZ'],
        ['AASCN1ZZ', null],
        ['ASCN1ZZA', null],
        ['TDCU1ZX', null],
        ['TDCU1ZZ', 'TDCU 1ZZ'],
        ['ATDCU1ZZ', null],
        ['TDCU1ZZA', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new SHHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
