<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class RichTextReference implements RichTextItem
{
    private const TYPE = 'reference';
    private RichTextItem|Collection $text;
    private string $name;

    public function __construct()
    {
        $this->text = Collection::empty();
    }

    /**
     * @param  string|array{
     *     type: string,
     *     text: string|array,
     *     name: string
     * }  $data
     *
     * @return RichTextReference
     */
    public static function fromData(string|array $data): RichTextReference
    {
        $richTextReference = new self();

        if (!is_array($data) || !isset($data['type']) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextReference->text = app(RichTextFactory::class)->fromData($data['text']);
        $richTextReference->name = $data['name'];

        return $richTextReference;
    }

    public function type(): ?string
    {
        return self::TYPE;
    }

    public function text(): RichTextItem|Collection
    {
        return $this->text;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn(RichTextItem $item) => $item->build())->toArray(),
            'name' => $this->name,
        ], fn($value) => $value !== null);
    }
}
