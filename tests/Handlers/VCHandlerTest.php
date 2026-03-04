<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\VCHandler;

describe('VCHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new VCHandler();

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
        ['1234', 'VC1234'],
        ['12345', null],
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['VC', null],
        ['VC1', null],
        ['VC123', null],
        ['VC1234', 'VC1234'],
        ['VX1234', null],
        ['XC1234', null],
        ['VC12345', null],
        ['XVC1234', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new VCHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
