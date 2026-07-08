<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\RichBlock\RichBlockDivider;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = RichBlockDivider::fromArray([
        'type' => 'divider',
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});

it('throw exception with wrong type', function () {
    expect(fn () => RichBlockDivider::fromArray(['type' => 'test']))
        ->toThrow(RichBlockException::structureMismatch(), 'The RichBlockItem provided structure is not valid');
});
