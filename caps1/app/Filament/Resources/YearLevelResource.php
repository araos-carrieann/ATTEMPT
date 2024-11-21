<?php

namespace App\Filament\Resources;

use App\Filament\Resources\YearLevelResource\Pages;
use App\Filament\Resources\YearLevelResource\RelationManagers;
use App\Models\YearLevel;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class YearLevelResource extends Resource
{
    protected static ?string $model = YearLevel::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationParentItem = "Users";
    protected static ?string $navigationGroup = "Accounts";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('yearlevel')
                ->label('Year Level')
                ->required()
                ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('yearlevel')
                    ->label('Year Level')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListYearLevels::route('/'),
            'create' => Pages\CreateYearLevel::route('/create'),
            'edit' => Pages\EditYearLevel::route('/{record}/edit'),
        ];
    }
}
