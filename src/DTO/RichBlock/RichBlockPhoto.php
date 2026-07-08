<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\DTO\Animation;
use DefStudio\Telegraph\DTO\Factories\RichBlockFactory;
use DefStudio\Telegraph\DTO\Location;
use DefStudio\Telegraph\DTO\Photo;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockElements\RichBlockCaption;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class RichBlockPhoto implements RichBlockItem, Arrayable
{
    private const TYPE = 'photo';
    private Collection $photos;
    private bool $hasSpoiler = false;
    private ?RichBlockCaption $caption = null;


    /**
     * @param  array{
     *     type: string,
     *     photo: array<string, mixed>,
     *     has_spoiler?: bool,
     *     caption?: array<string, mixed>
     * }  $data
     *
     * @return RichBlockPhoto
     */
    public static function fromArray(array $data): RichBlockPhoto
    {
        if (!isset($data['type']) || $data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockPhoto = new self();

        /* @phpstan-ignore-next-line */
        $richBlockPhoto->photos = collect($data['photo'] ?? [])->map(fn(array $photoData) => Photo::fromArray($photoData));

        $richBlockPhoto->hasSpoiler = $data['has_spoiler'] ?? false;

        if (isset($data['caption']) && $data['caption']) {
            $richBlockPhoto->caption = RichBlockCaption::fromArray($data['caption']);
        }

        return $richBlockPhoto;
    }

    public function type(): string
    {
        return self::TYPE;
    }

    /**
     * @return Collection<array-key, Photo>
     */
    public function photos(): Collection
    {
        return $this->photos;
    }

    public function hasSpoiler(): bool
    {
        return $this->hasSpoiler;
    }

    public function caption(): ?RichBlockCaption
    {
        return $this->caption;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'photos' => $this->photos->toArray(),
            'has_spoiler' => $this->hasSpoiler ? true : null,
            'caption' => $this->caption?->toArray(),
        ], fn($value) => $value !== null);
    }
}
