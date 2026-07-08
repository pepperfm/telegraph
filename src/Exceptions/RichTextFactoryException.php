<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

final class RichTextFactoryException extends Exception
{
    public static function invalidType(string $type): RichTextFactoryException
    {
        return new self(sprintf("Invalid Rich Block Type: `%s`", $type));
    }
}
