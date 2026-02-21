<?php

namespace App\Enums;

enum ProductState: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Invisible = 'invisible';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Brouillon',
            self::Published => 'Publié',
            self::Invisible => 'Invisible',
        };
    }

    /** Returns [value => label] for use in select inputs. */
    public static function options(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_map(fn (self $case): string => $case->label(), self::cases()),
        );
    }
}
