<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\Audio;
use DefStudio\Telegraph\DTO\RichText\RichTextString;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Str;

it('export all properties', function() {
    $dto = RichTextString::fromData('test');

    $string = $dto->build();

    expect($string)->toBe('test');
});

it('throw exception with wrong data structure', function() {
        expect(fn() => RichTextString::fromData(['test']))
            ->toThrow(RichTextException::structureMismatch(),'The RichTextItem provided structure is not valid');
});
