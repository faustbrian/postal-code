<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\PNHandler;

describe('PNHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new PNHandler();

        if ($expected === null) {
            expect($handler->validate($input))->toBeFalse();
        } else {
            expect($handler->validate($input))->toBeTrue();
            expect($handler->format($input))->toBe($expected);
        }
    })->with([
        ['', null],
        ['1234567', null],
        ['PCRN1ZX', null],
        ['PCRN1ZZ', 'PCRN 1ZZ'],
        ['APCRN1ZZ', null],
        ['PCRN1ZZA', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new PNHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
