<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\AZHandler;

describe('AZHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new AZHandler();

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
        ['1234', 'AZ 1234'],
        ['12345', null],
        ['123456', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
        ['AZ', null],
        ['AZ1', null],
        ['AZ123', null],
        ['AZ1234', 'AZ 1234'],
        ['AZ12345', null],
        ['XAZ1234', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new AZHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
