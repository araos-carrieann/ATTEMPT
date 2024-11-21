<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use Filament\Infolists\Infolist;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $navigationGroup = "Accounts";
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-s-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Student Information')
                    ->schema([
                        TextInput::make('student_id')
                            ->label('Student ID')
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('username')
                            ->label('Username')
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('last_name')
                            ->label('Last Name')
                            ->required(),

                        TextInput::make('first_name')
                            ->label('First Name')
                            ->required(),

                        TextInput::make('middle_name')
                            ->label('Middle Name'),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(),

                        DatePicker::make('birthdate')
                            ->label('Birthdate')
                            ->required(),

                        Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                            ])
                            ->required(),

                        Select::make('program_id')
                            ->label('Program')
                            ->relationship('programs', 'name')
                            ->required(),

                        Select::make('year_level_id')
                            ->label('Year Level')
                            ->relationship('year_levels', 'yearlevel')
                            ->required(),

                        TextInput::make('password')
                            ->password()
                            ->label('Password')
                            ->required()
                            ->visibleOn('create'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'primary',
                        'inactive' => 'warning',
                        'active' => 'success',
                        'rejected' => 'danger',
                    })
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('username')
                    ->label('Username')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('last_name')
                    ->label('Last Name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('first_name')
                    ->label('First Name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('middle_name')
                    ->label('Middle Name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('birthdate')
                    ->label('Birthdate')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('gender')
                    ->label('Gender')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('programs.name')
                    ->label('Program')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('year_levels.yearlevel')
                    ->label('Year Level')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
            ])

            ->filters([
                //

                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'inactive' => 'Inactive',
                        'active' => 'Active',
                        'rejected' => 'Rejected',
                    ])
                    ->multiple(),


            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Personal Information')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('username')
                                    ->label('Username')
                                    ->fontFamily(FontFamily::Mono),

                                TextEntry::make('email')
                                    ->label('Email')
                                    ->icon('heroicon-m-envelope')
                                    ->iconColor('primary'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextEntry::make('first_name')
                                    ->label('First Name')
                                    ->fontFamily(FontFamily::Mono),

                                TextEntry::make('middle_name')
                                    ->label('Middle Name')
                                    ->fontFamily(FontFamily::Mono),

                                TextEntry::make('last_name')
                                    ->label('Last Name')
                                    ->fontFamily(FontFamily::Mono),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('gender')
                                    ->label('Gender'),

                                TextEntry::make('birthdate')
                                    ->label('Birthdate')
                                    ->icon('heroicon-o-calendar'),
                            ]),
                    ]),

                Section::make('Academic Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('programs.name')
                                    ->label('Program'),

                                TextEntry::make('year_levels.yearlevel')
                                    ->label('Year Level'),
                            ]),
                    ]),

                Section::make('Account Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('role')
                                    ->label('Role'),

                                TextEntry::make('status')
                                    ->label('Status')
                                    ->icon(fn($record) => match ($record->status) {
                                        'active' => 'heroicon-o-check-circle',
                                        'pending' => 'heroicon-o-clock',
                                        'inactive' => 'heroicon-o-x-circle',
                                        'rejected' => 'heroicon-o-exclamation-circle',
                                    })
                                    ->color(fn($record) => match ($record->status) {
                                        'active' => 'success',
                                        'pending' => 'warning',
                                        'inactive' => 'secondary',
                                        'rejected' => 'danger',
                                    }),
                            ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
