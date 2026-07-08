<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\Factories\RichTextFactory;
use DefStudio\Telegraph\Exceptions\RichTextException;
use Illuminate\Support\Collection;

class RichTextBotCommand implements RichTextItem
{
    private const TYPE = 'bot_command';
    private RichTextItem|Collection $text;
    private string $botCommand;

    public function __construct()
    {
        $this->text = Collection::empty();
    }

    /**
     * @param  string|array{
     *     type: string,
     *     text: string|array,
     *     bot_command: string
     * }  $data
     *
     * @return RichTextBotCommand
     */
    public static function fromData(string|array $data): RichTextBotCommand
    {
        $richTextBotCommand = new self();

        if (!is_array($data) || !isset($data['type']) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextBotCommand->text = app(RichTextFactory::class)->fromData($data['text']);
        $richTextBotCommand->botCommand = $data['bot_command'];

        return $richTextBotCommand;
    }

    public function type(): ?string
    {
        return self::TYPE;
    }

    public function text(): RichTextItem|Collection
    {
        return $this->text;
    }

    public function botCommand(): string
    {
        return $this->botCommand;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'text' => $this->text instanceof RichTextItem
                ? $this->text->build()
                : $this->text->map(fn (RichTextItem $item) => $item->build())->toArray(),
            'bot_command' => $this->botCommand,
        ], fn ($value) => $value !== null);
    }
}
