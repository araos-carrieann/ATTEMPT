<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use App\Models\eBooks;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class TrashedEbooks extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationGroup = "EBook";
    protected static ?string $navigationLabel = 'Trashed eBooks';
    protected static ?string $navigationIcon = 'heroicon-o-trash';  
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.pages.trashed-ebooks';

    protected function getTableQuery(): Builder
    {
        return eBooks::onlyTrashed(); // Only get trashed eBooks
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')->sortable()->searchable(),
            TextColumn::make('author')->sortable()->searchable(),
            TextColumn::make('deleted_at')
                ->label('Deleted At')
                ->sortable()
                ->dateTime(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('restore')
                ->label('Restore')
                ->action(fn ($record) => $record->restore())
                ->color('success')
                ->icon('heroicon-o-arrow-path'),

            Tables\Actions\Action::make('force_delete')
                ->label('Force Delete')
                ->action(fn ($record) => $record->forceDelete())
                ->color('danger')
                ->icon('heroicon-o-trash'),
        ];
    }
}
