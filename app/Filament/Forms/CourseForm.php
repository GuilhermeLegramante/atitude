<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;

class CourseForm
{
    public static function form(): array
    {
        return [
            FormFields::name(),
            FormFields::description(required: false),
            FormFields::note(),
        ];
    }
}
