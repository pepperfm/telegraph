<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Collection;

class RichTextSpoiler implements RichTextItem
{
    private const TYPE = 'spoiler';
    private RichTextItem|Collection $text;

    public function __construct()
    {
        $this->text = Collection::empty();
    }

    /**
     * @param  string|array{
     *     type: string,
     *     text: string|array
     * }  $data
     *
     * @return RichTextSpoiler
     */
    public static function fromData(string|array $data): RichTextSpoiler
    {
        $richTextSpoiler = new self();

        if (!is_array($data) || !isset($data['type']) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextSpoiler->text = app(RichTextFactory::class)->fromData($data['text']);

        return $richTextSpoiler;
    }

    public function type(): ?string
    {
        return self::TYPE;
    }

    public function text(): RichTextItem|Collection
    {
        return $this->text;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
        ], fn ($value) => $value !== null);
    }
}
