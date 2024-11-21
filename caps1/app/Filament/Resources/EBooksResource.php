<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EBooksResource\Pages;
use App\Models\Category;
use App\Models\EBooks;
use App\Models\Subcategory;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;



class EBooksResource extends Resource
{
    protected static ?string $model = EBooks::class;
    protected static ?string $navigationGroup = "EBook";
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-s-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Basic Information')
                    ->schema([
                        TextInput::make('title')
                            ->live(onBlur: true)
                            ->required()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $slug = Str::slug($state . '-' . $get('author'));
                                $set('slug', $slug);
                            }),

                        Grid::make()
                            ->schema([
                                Select::make('category_id')
                                    ->label('Category')
                                    ->options(Category::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),

                                Select::make('subcategories')
                                    ->label('Subcategories')
                                    ->multiple() // Allow selecting multiple subcategories
                                    ->options(Subcategory::all()->mapWithKeys(function ($subcategory) {
                                        return [$subcategory->id => "{$subcategory->letter} - {$subcategory->name}"];
                                    }))
                                    ->searchable()
                                    ->required(),

                                TextInput::make('code')
                                    ->label('Code')
                                    ->required(),

                            ])
                            ->columns(3),

                        TextInput::make('author')
                            ->live(onBlur: true)
                            ->required()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $title = $get('title');
                                $slug = Str::slug($title . '-' . $state);
                                $set('slug', $slug);
                            }),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('publication_year')->required(),

                        TextInput::make('publisher')
                            ->label('Publisher')
                            ->required(),

                        TextInput::make('isbn')
                            ->label('ISBN')
                            ->nullable()
                            ->unique(ignoreRecord: true),

                        Select::make('language')
                            ->label('Language')
                            ->options([
                                'en' => 'English',
                                'ph' => 'Filipino',
                            ])
                            ->default('en') // Set the default language to English
                            ->required()
                            ->live(),
                    ]),


                Fieldset::make('Details')
                    ->schema([
                        MarkdownEditor::make('description')->required(),
                        SpatieTagsInput::make('tags')->required(),

                    ]),

                Fieldset::make('Publishing Options')
                    ->schema([
                        Checkbox::make('published')->required(),
                    ]),

                Fieldset::make('Files')
                    ->schema([
                        FileUpload::make('book_cover')
                            ->disk('public')
                            ->directory('BookCovers')
                            ->label('Book Cover')
                            ->fetchFileInformation(false)
                            ->required(),

                        FileUpload::make('ebook_file_path')
                            ->disk('public')
                            ->directory('eBooks')
                            ->required()
                            ->label('EBook File')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('book_cover')
                    ->label('Book Cover'),

                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('category.name')
                    ->label('Category Name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                SpatieTagsColumn::make('tags')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),


            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->preload()
                    ->multiple()
                    ->searchable()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListEBooks::route('/'),
            'create' => Pages\CreateEBooks::route('/create'),
            'edit' => Pages\EditEBooks::route('/{record}/edit'),
        ];
    }
}
