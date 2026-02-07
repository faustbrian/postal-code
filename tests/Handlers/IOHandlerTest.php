<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\IOHandler;

describe('IOHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new IOHandler();

        if ($expected === null) {
            expect($handler->validate($input))->toBeFalse();
        } else {
            expect($handler->validate($input))->toBeTrue();
            expect($handler->format($input))->toBe($expected);
        }
    })->with([
        ['', null],
        ['1234567', null],
        ['BBND1ZX', null],
        ['BBND1ZZ', 'BBND 1ZZ'],
        ['ABBND1ZZ', null],
        ['BBND1ZZA', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new IOHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
