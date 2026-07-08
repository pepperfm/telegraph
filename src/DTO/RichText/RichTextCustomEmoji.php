<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class RichTextCustomEmoji implements RichTextItem
{
    private const TYPE = 'custom_emoji';

    private string $customEmojiId;
    private string $alternativeText;

    /**
     * @param  string|array{
     *     type: string,
     *     custom_emoji_id: string,
     *     alternative_text: string
     * }  $data
     *
     * @return RichTextCustomEmoji
     */
    public static function fromData(string|array $data): RichTextCustomEmoji
    {
        $richTextCustomEmoji = new self();

        if (!is_array($data) || !isset($data['type']) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextCustomEmoji->customEmojiId = $data['custom_emoji_id'];
        $richTextCustomEmoji->alternativeText = $data['alternative_text'];

        return $richTextCustomEmoji;
    }

    public function type(): ?string
    {
        return self::TYPE;
    }

    public function customEmojiId(): string
    {
        return $this->customEmojiId;
    }

    public function alternativeText(): string
    {
        return $this->alternativeText;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'custom_emoji_id' => $this->customEmojiId,
            'alternative_text' => $this->alternativeText,
        ], fn($value) => $value !== null);
    }
}
