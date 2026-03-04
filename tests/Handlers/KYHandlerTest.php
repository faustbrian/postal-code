<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\KYHandler;

describe('KYHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new KYHandler();

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
        ['A', null],
        ['AB', null],
        ['ABC', null],
        ['ABCD', null],
        ['ABCDE', null],
        ['ABCDEF', null],
        ['KY01234', null],
        ['KY12345', 'KY1-2345'],
        ['KY23456', 'KY2-3456'],
        ['KY34567', 'KY3-4567'],
        ['KY45678', null],
        ['KYX1234', null],
        ['KX12345', null],
        ['XY12345', null],
        ['KKY1234', null],
        ['KKY12345', null],
        ['KY1234', null],
        ['KY123456', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new KYHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
