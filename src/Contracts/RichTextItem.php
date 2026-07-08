<?php

namespace DefStudio\Telegraph\Contracts;

interface RichTextItem
{
    public static function fromData(string|array $data): self;

    public function type(): ?string;

    public function build(): array|string;
}
