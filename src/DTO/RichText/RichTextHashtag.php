<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Collection;

class RichTextHashtag implements RichTextItem
{
    private const TYPE = 'hashtag';
    private RichTextItem|Collection $text;
    private string $hashtag;

    public function __construct()
    {
        $this->text = Collection::empty();
    }

    /**
     * @param  string|array{
     *     type: string,
     *     text: string|array,
     *     hashtag: string
     * }  $data
     *
     * @return RichTextHashtag
     */
    public static function fromData(string|array $data): RichTextHashtag
    {
        $richTextHashtag = new self();

        if (!is_array($data) || !isset($data['type']) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextHashtag->text = app(RichTextFactory::class)->fromData($data['text']);
        $richTextHashtag->hashtag = $data['hashtag'];

        return $richTextHashtag;
    }

    public function type(): ?string
    {
        return self::TYPE;
    }

    public function text(): RichTextItem|Collection
    {
        return $this->text;
    }

    public function hashtag(): string
    {
        return $this->hashtag;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'hashtag' => $this->hashtag,
        ], fn ($value) => $value !== null);
    }
}
