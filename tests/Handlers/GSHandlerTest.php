<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\GSHandler;

describe('GSHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new GSHandler();

        if ($expected === null) {
            expect($handler->validate($input))->toBeFalse();
        } else {
            expect($handler->validate($input))->toBeTrue();
            expect($handler->format($input))->toBe($expected);
        }
    })->with([
        ['', null],
        ['1234567', null],
        ['SIQQ0ZZ', null],
        ['SIQQ1ZZ', 'SIQQ 1ZZ'],
        ['SIQQ1AB', null],
        ['AIQQ1ZZ', null],
        ['SIQQ1ZZA', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new GSHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
