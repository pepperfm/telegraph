<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;

class RichBlockDivider implements RichBlockItem, Arrayable
{
    private const TYPE = 'divider';

    public static function fromArray(array $data): RichBlockDivider
    {
        if (!isset($data['type']) || $data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        return new self();
    }

    public function type(): string
    {
        return self::TYPE;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
        ], fn($value) => $value !== null);
    }
}
