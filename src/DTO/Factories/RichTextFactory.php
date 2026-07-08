<?php

namespace DefStudio\Telegraph\DTO\Factories;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\DTO\RichText\RichTextAnchor;
use DefStudio\Telegraph\DTO\RichText\RichTextAnchorLink;
use DefStudio\Telegraph\DTO\RichText\RichTextBankCardNumber;
use DefStudio\Telegraph\DTO\RichText\RichTextBold;
use DefStudio\Telegraph\DTO\RichText\RichTextBotCommand;
use DefStudio\Telegraph\DTO\RichText\RichTextCashtag;
use DefStudio\Telegraph\DTO\RichText\RichTextCode;
use DefStudio\Telegraph\DTO\RichText\RichTextCustomEmoji;
use DefStudio\Telegraph\DTO\RichText\RichTextDateTime;
use DefStudio\Telegraph\DTO\RichText\RichTextEmailAddress;
use DefStudio\Telegraph\DTO\RichText\RichTextHashtag;
use DefStudio\Telegraph\DTO\RichText\RichTextItalic;
use DefStudio\Telegraph\DTO\RichText\RichTextMarked;
use DefStudio\Telegraph\DTO\RichText\RichTextMathematicalExpression;
use DefStudio\Telegraph\DTO\RichText\RichTextMention;
use DefStudio\Telegraph\DTO\RichText\RichTextPhoneNumber;
use DefStudio\Telegraph\DTO\RichText\RichTextReference;
use DefStudio\Telegraph\DTO\RichText\RichTextReferenceLink;
use DefStudio\Telegraph\DTO\RichText\RichTextSpoiler;
use DefStudio\Telegraph\DTO\RichText\RichTextStrikethrough;
use DefStudio\Telegraph\DTO\RichText\RichTextString;
use DefStudio\Telegraph\DTO\RichText\RichTextSubscript;
use DefStudio\Telegraph\DTO\RichText\RichTextSuperscript;
use DefStudio\Telegraph\DTO\RichText\RichTextTextMention;
use DefStudio\Telegraph\DTO\RichText\RichTextUnderline;
use DefStudio\Telegraph\DTO\RichText\RichTextUrl;
use DefStudio\Telegraph\Exceptions\RichTextException;
use DefStudio\Telegraph\Exceptions\RichTextFactoryException;
use Illuminate\Support\Collection;

class RichTextFactory
{
    /**
     * @param  string|array<array-key,string|Object>  $data
     *
     * @return RichTextItem|Collection<RichTextItem>
     * @throws RichTextException
     * @throws RichTextFactoryException
     */
    public function fromData(string|array $data): RichTextItem|Collection
    {
        if (is_string($data)) {
            return RichTextString::fromData($data);
        }

        if (is_array($data) && !isset($data['type'])) {
            return collect($data)->map(fn ($item) => $this->fromData($item));
        }

        return match ($data['type']) {
            app(RichTextBold::class)->type() => RichTextBold::fromData($data),
            app(RichTextItalic::class)->type() => RichTextItalic::fromData($data),
            app(RichTextUnderline::class)->type() => RichTextUnderline::fromData($data),
            app(RichTextStrikethrough::class)->type() => RichTextStrikethrough::fromData($data),
            app(RichTextSpoiler::class)->type() => RichTextSpoiler::fromData($data),
            app(RichTextDateTime::class)->type() => RichTextDateTime::fromData($data),
            app(RichTextTextMention::class)->type() => RichTextTextMention::fromData($data),
            app(RichTextSubscript::class)->type() => RichTextSubscript::fromData($data),
            app(RichTextSuperscript::class)->type() => RichTextSuperscript::fromData($data),
            app(RichTextMarked::class)->type() => RichTextMarked::fromData($data),
            app(RichTextCode::class)->type() => RichTextCode::fromData($data),
            app(RichTextCustomEmoji::class)->type() => RichTextCustomEmoji::fromData($data),
            app(RichTextMathematicalExpression::class)->type() => RichTextMathematicalExpression::fromData($data),
            app(RichTextUrl::class)->type() => RichTextUrl::fromData($data),
            app(RichTextEmailAddress::class)->type() => RichTextEmailAddress::fromData($data),
            app(RichTextPhoneNumber::class)->type() => RichTextPhoneNumber::fromData($data),
            app(RichTextBankCardNumber::class)->type() => RichTextBankCardNumber::fromData($data),
            app(RichTextMention::class)->type() => RichTextMention::fromData($data),
            app(RichTextHashtag::class)->type() => RichTextHashtag::fromData($data),
            app(RichTextCashtag::class)->type() => RichTextCashtag::fromData($data),
            app(RichTextBotCommand::class)->type() => RichTextBotCommand::fromData($data),
            app(RichTextAnchor::class)->type() => RichTextAnchor::fromData($data),
            app(RichTextAnchorLink::class)->type() => RichTextAnchorLink::fromData($data),
            app(RichTextReference::class)->type() => RichTextReference::fromData($data),
            app(RichTextReferenceLink::class)->type() => RichTextReferenceLink::fromData($data),

            default => throw RichTextFactoryException::invalidType($data['type'])
        };
    }
}
