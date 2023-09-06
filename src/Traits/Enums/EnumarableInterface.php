<?php

namespace Slakbal\Tools\Traits\Enums;

interface EnumarableInterface
{
    public function label(): string;

    public function styles(): string;

    public static function hiddenCases(): array;
}
