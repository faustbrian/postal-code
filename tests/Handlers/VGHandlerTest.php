<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\VGHandler;

describe('VGHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new VGHandler();

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
        ['1109', null],
        ['1110', 'VG1110'],
        ['1111', 'VG1111'],
        ['1159', 'VG1159'],
        ['1160', 'VG1160'],
        ['1161', null],
        ['12345', null],
        ['XXXX', null],
        ['A', null],
        ['AB', null],
        ['AB1110', null],
        ['AVG1110', null],
        ['VG01110', null],
        ['VG11100', null],
        ['VGXXXX', null],
        ['VG', null],
        ['VG1', null],
        ['VG12', null],
        ['VG123', null],
        ['VG1109', null],
        ['VG1110', 'VG1110'],
        ['VG1111', 'VG1111'],
        ['VG1159', 'VG1159'],
        ['VG1160', 'VG1160'],
        ['VG1161', null],
        ['AB123', null],
        ['VGZ12', null],
        ['A1234', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new VGHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
