<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockElements\RichBlockListItem;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class RichBlockList implements RichBlockItem, Arrayable
{
    private const TYPE = 'list';
    /** @var Collection<array-key, RichBlockListItem> */
    private Collection $items;

    /**
     * @param  array{
     *     type: string,
     *     items: array<array-key,Object>,
     * }  $data
     *
     * @return RichBlockList
     */
    public static function fromArray(array $data): RichBlockList
    {
        if (!isset($data['type']) || $data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockList = new self();

        if (isset($data['items']) && $data['items']) {
            /* @phpstan-ignore-next-line */
            $richBlockList->items = collect($data['items'])->map(fn(array $listItem) => RichBlockListItem::fromArray($listItem));
        }

        return $richBlockList;
    }

    public function type(): string
    {
        return self::TYPE;
    }

    /**
     * @return Collection<array-key, RichBlockListItem>
     */
    public function items(): Collection
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'items' => $this->items->toArray(),
        ], fn($value) => $value !== null);
    }
}
