<?php

namespace Slakbal\Tools\Traits\Enums;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait Enumarable
{
    //resolve Enum from value
    public static function parseFrom($value): self
    {
        //if already a Enumarable, just return it
        if ($value instanceof static) {
            return $value;
        }

        if (is_numeric($value)) {
            return self::from(Str::remove('.0', $value));
        }

        return self::from(str($value)->trim()->replace('/', ''));
    }

    //get the transalated names for the keys
    public function label(): string
    {
        return __($this->name);
        /*
        return match ($this) {
            self::ENUM_A => __('Enum A'),
            self::ENUM_B => __('Enum B'),
            default => __($this->name) //Provide User Friendly Name in the getName method
        };
        */
    }

    //get styles for each of the cases
    public function styles(): string
    {
        return '';
        /*
        return match ($this) {
            self::ENUM_A => 'bg-blue-200 text-blue-800',
            self::ENUM_B => 'bg-red-200 text-red-800',
            default => 'bg-gray-200 text-gray-800' //default when no styles provided
        };
        */
    }

    //returns the array of cases that can i.e. not be selected to be created
    public static function hiddenCases(): array
    {
        return [];
    }

    public function is($enum): bool
    {
        if ($enum instanceof static) {
            return $this === $enum;
        }

        return $this->value === $enum;
    }

    public function isNot($enum): bool
    {
        if ($enum instanceof static) {
            return $this !== $enum;
        }

        return $this->value !== $enum;
    }

    public function isEnum($enum): bool
    {
        if ($enum instanceof static) {
            return true;
        }

        throw new Exception('Enums must be an instance of '.static::class);
    }

    public function in(array $enums): bool
    {
        foreach ($enums as $enum) {
            if ($this->isEnum($enum)) {
                if ($this === $enum) {
                    return true;
                }
            }
        }

        return false;
    }

    public function notIn(array $enums): bool
    {
        foreach ($enums as $enum) {
            if ($this->isEnum($enum)) {
                if ($this === $enum) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($enum) => [
                $enum->value => $enum->label(),
            ])
            ->toArray();
    }

    //returns only the name values of the cases as an array
    public static function optionValues(): array
    {
        return array_map(fn ($case): string => $case->value, self::cases());
    }

    public static function optionNames(): array
    {
        return array_map(fn ($case): string => $case->name, self::cases());
    }

    public static function selectOptions(bool $prependSelectionPromptOption = false, string $prependOptionLabel = 'All', bool $sortOptions = true, bool $excludeHiddenCases = true): array
    {
        $options = self::options();

        //per default filter out the hidden cases
        if ($excludeHiddenCases) {
            Arr::forget($options, array_map(fn ($case): string => $case->value, self::hiddenCases())); //exclude the hidden cases
        }

        if ($sortOptions) {
            // $options = Arr::sort($options);
            /*
                0 = SORT_REGULAR - Default. Compare items normally (don't change types)
                1 = SORT_NUMERIC - Compare items numerically
                2 = SORT_STRING - Compare items as strings
                3 = SORT_LOCALE_STRING - Compare items as strings, based on current locale
                4 = SORT_NATURAL - Compare items as strings using natural ordering
                5 = SORT_FLAG_CASE -
            */
            asort($options, SORT_NATURAL);
        }

        return $prependSelectionPromptOption ? Arr::prepend($options, __($prependOptionLabel), '0') : $options;
    }

    public static function getRandom(): self
    {
        return Arr::random(self::cases());
    }
}
