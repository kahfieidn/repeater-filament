<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Formulir;
use Filament\Forms\Form;
use App\Models\Perizinan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FormulirResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FormulirResource\RelationManagers;

class FormulirResource extends Resource
{
    protected static ?string $model = Formulir::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('perizinan_id')
                    ->options(Perizinan::all()->pluck('nama_perizinan', 'id')->toArray())
                    ->required(),
                Forms\Components\TextInput::make('nama_formulir')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                ->options([
                    'string' => 'String',
                    'date' => 'Date',
                    'select' => 'Select',
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('perizinan_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_formulir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListFormulirs::route('/'),
            'create' => Pages\CreateFormulir::route('/create'),
            'view' => Pages\ViewFormulir::route('/{record}'),
            'edit' => Pages\EditFormulir::route('/{record}/edit'),
        ];
    }
}
