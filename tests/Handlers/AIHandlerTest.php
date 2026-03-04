<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\PostalCode\Handlers\AIHandler;

describe('AIHandler', function (): void {
    test('validates and formats postalCodes', function (string $input, ?string $expected): void {
        $handler = new AIHandler();

        if ($expected === null) {
            expect($handler->validate($input))->toBeFalse();
        } else {
            expect($handler->validate($input))->toBeTrue();
            expect($handler->format($input))->toBe($expected);
        }
    })->with([
        ['', null],
        ['2640', 'AI-2640'],
        ['2641', null],
        ['AI', null],
        ['AB2650', null],
        ['AI2640', 'AI-2640'],
        ['AI2641', null],
        ['AI26401', null],
        ['0AI2640', null],
    ]);

    test('provides a hint', function (): void {
        $handler = new AIHandler();

        expect($handler->hint())->toBeString()->not->toBeEmpty();
    });
});
