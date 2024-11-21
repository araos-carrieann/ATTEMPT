<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubcategoryResource\Pages;
use App\Filament\Resources\SubcategoryResource\RelationManagers;
use App\Models\Subcategory;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
class SubcategoryResource extends Resource
{
    protected static ?string $model = Subcategory::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationParentItem = "E Books";
    protected static ?string $navigationGroup = "EBook";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('letter')
                ->live(onBlur: true)
                ->label('Letter')
                ->unique(ignoreRecord: true)
                ->required()
                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                    $slug = Str::slug($state . '-' . $get('name'));
                    $set('slug', $slug);
                }),

                TextInput::make('name')
                ->label('Category')
                ->required()
                ->unique(ignoreRecord: true),

                
                TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('letter')
                ->label('Letter')
                ->sortable()
                ->searchable()
                ->toggleable(),

                TextColumn::make('name')
                ->label('Program')
                ->sortable()
                ->searchable()
                ->toggleable(),

                TextColumn::make('slug')
                ->label('Slug')
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
            'index' => Pages\ListSubcategories::route('/'),
            'create' => Pages\CreateSubcategory::route('/create'),
            'edit' => Pages\EditSubcategory::route('/{record}/edit'),
        ];
    }
}
