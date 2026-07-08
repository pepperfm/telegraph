<?php

namespace DefStudio\Telegraph\Contracts;

interface RichBlockItem
{
    public static function fromArray(array $data): self;

    public function type(): string;
}
