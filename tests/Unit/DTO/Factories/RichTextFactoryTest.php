<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Audio;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\DTO\RichText\RichTextBold;
use DefStudio\Telegraph\DTO\RichText\RichTextString;
use DefStudio\Telegraph\Exceptions\RichTextException;
use DefStudio\Telegraph\Exceptions\RichTextFactoryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

it('throw exception with wrong data structure', function() {
    expect(fn() => app(RichTextFactory::class)->fromData(['type' => 'test']))
        ->toThrow(RichTextFactoryException::invalidType('test'), 'Invalid Factory Rich Text Item Type: `test`');
});

it('throw exception with wrong type', function() {
    expect(fn() => app(RichTextFactory::class)->fromData([
        ['test'],
        [['test']],
    ]))
        ->toThrow(RichTextFactoryException::structureMismatch(), 'The Factory Rich Text provided structure is not valid');
});

