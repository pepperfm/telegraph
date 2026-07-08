<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class RichTextAnchorLink implements RichTextItem
{
    private const TYPE = 'anchor_link';
    private RichTextItem|Collection $text;
    private string $anchorName;

    public function __construct()
    {
        $this->text = Collection::empty();
    }

    /**
     * @param  string|array{
     *     type: string,
     *     text: string|array,
     *     anchor_name: string
     * }  $data
     *
     * @return RichTextAnchorLink
     */
    public static function fromData(string|array $data): RichTextAnchorLink
    {
        $richTextAnchorLink = new self();

        if (!is_array($data) || !isset($data['type']) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextAnchorLink->text = app(RichTextFactory::class)->fromData($data['text']);
        $richTextAnchorLink->anchorName = $data['anchor_name'];

        return $richTextAnchorLink;
    }

    public function type(): ?string
    {
        return self::TYPE;
    }


    public function text(): RichTextItem|Collection
    {
        return $this->text;
    }

    public function anchorName(): string
    {
        return $this->anchorName;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn(RichTextItem $item) => $item->build())->toArray(),
            'anchor_name' => $this->anchorName,
        ], fn($value) => $value !== null);
    }
}
