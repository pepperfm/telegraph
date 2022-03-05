<?php

namespace DefStudio\Telegraph\Keyboard;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Keyboard
{
    /** @var Collection<array-key, Button> */
    private Collection $buttons;

    private function __construct()
    {
        /* @phpstan-ignore-next-line  */
        $this->buttons = collect();
    }

    public static function make(): Keyboard
    {
        return new self();
    }

    /**
     * @param string[][][] $arrayKeyboard
     *
     * @return Keyboard
     */
    public static function fromArray(array $arrayKeyboard): Keyboard
    {
        $keyboard = self::make();

        foreach ($arrayKeyboard as $buttons) {
            $rowButtons = [];

            foreach ($buttons as $button) {
                $rowButton = Button::make($button['text']);

                if (array_key_exists("callback_data", $button)) {
                    $params = explode(";", $button['callback_data']);

                    foreach ($params as $param) {
                        $key = Str::of($param)->before(':');
                        $value = Str::of($param)->after(':');

                        $rowButton = $rowButton->param($key, $value);
                    }
                }

                if (array_key_exists("url", $button)) {
                    $rowButton = $rowButton->url($button['url']);
                }

                $rowButtons[] = $rowButton;
            }

            $keyboard = $keyboard->row($rowButtons);
        }

        return $keyboard;
    }

    /**
     * @param array<array-key, Button>|Collection<array-key, Button> $buttons
     *
     * @return Keyboard
     */
    public function row(array|Collection $buttons): Keyboard
    {
        $clone = clone $this;

        if (is_array($buttons)) {
            $buttons = collect($buttons);
        }

        $buttonWidth = 1 / $buttons->count();

        $buttons = $buttons->map(fn (Button $button) => $button->width($buttonWidth));

        $this->buttons->push(...$buttons);

        return $clone;
    }

    public function chunk(int $chunk): Keyboard
    {
        $clone = clone $this;

        $buttonWidth = 1 / $chunk;

        $clone->buttons = $this->buttons->map(fn (Button $button) => $button->width($buttonWidth));

        return $clone;
    }

    /**
     * @param array<array-key, Button>|Collection<array-key, Button> $buttons $buttons
     *
     * @return Keyboard
     */
    public function buttons(array|Collection $buttons): Keyboard
    {
        $clone = clone $this;

        if (is_array($buttons)) {
            $buttons = collect($buttons);
        }

        $clone->buttons->push(...$buttons);

        return $clone;
    }

    public function replaceButton(string $label, Button $newButton): Keyboard
    {
        $clone = clone $this;

        $clone->buttons = $clone->buttons->map(function (Button $button) use ($newButton, $label) {
            if ($button->label() == $label) {
                if (!$newButton->has_width()) {
                    $newButton = $newButton->width($button->get_width());
                }

                return $newButton;
            }

            return $button;
        });

        return $clone;
    }

    public function deleteButton(string $label): Keyboard
    {
        $clone = clone $this;

        /* @phpstan-ignore-next-line  */
        $clone->buttons = $clone->buttons->reject(fn (Button $button) => $button->label() == $label);

        return $clone;
    }

    public function flatten(): Keyboard
    {
        $clone = clone $this;

        $clone->buttons = $clone->buttons->map(fn (Button $button) => $button->width(1));

        return $clone;
    }

    public function isEmpty(): bool
    {
        return $this->buttons->isEmpty();
    }

    /**
     * @return string[][][]
     */
    public function toArray(): array
    {
        $keyboard = [];

        $row = [];
        $rowWidth = 0;

        $this->buttons->each(function (Button $button) use (&$keyboard, &$row, &$rowWidth): void {
            if ($rowWidth + $button->get_width() > 1) {
                $keyboard[] = $row;
                $row = [];
                $rowWidth = 0;
            }

            $row[] = $button->toArray();
            $rowWidth += $button->get_width();
        });

        $keyboard[] = $row;

        return $keyboard;
    }
}
