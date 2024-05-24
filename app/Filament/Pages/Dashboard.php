<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BD;

class Dashboard extends BD 
{
    use BD\Concerns\HasFiltersForm;

    public function filtersForm(Form $form): form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                DatePicker::make('startDate')
                    ->label('Tanggal Mulai')
                    ->default(now()->firstOfMonth())
                    ->maxDate(fn (Get $get) => $get('endDate') ?: now()),
                DatePicker::make('endDate')
                    ->label('Tanggal Akhir')
                    ->minDate(fn (Get $get) => $get('startDate') ?: now())
                    ->default(now()),
                ])
            ->columns(2),
                ]);
    }
}