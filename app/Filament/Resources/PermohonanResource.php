<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Formulir;
use Filament\Forms\Form;
use App\Models\Perizinan;
use App\Models\Permohonan;
use Filament\Tables\Table;
use App\Models\Persyaratan;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PermohonanResource\Pages;
use App\Filament\Resources\PermohonanResource\RelationManagers;

class PermohonanResource extends Resource
{
    protected static ?string $model = Permohonan::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Wizard::make([
                    Wizard\Step::make('Pilih Jenis Perizinan')
                        ->schema([
                            Forms\Components\Select::make('perizinan_id')
                                ->relationship(name: 'perizinan', titleAttribute: 'nama_perizinan')
                                ->preload()
                                ->live()
                                ->searchable()
                                ->afterStateUpdated(function (Set $set) {
                                    $set('berkas.*.nama_persyaratan', '');
                                    $set('berkas.*.file', null);
                                })
                                ->required(),
                        ]),
                    Wizard\Step::make('Unggah Berkas')
                        ->schema([
                            Repeater::make('berkas')
                                ->schema(function (Get $get): array {
                                    $selectedOptions = collect($get('berkas.*.nama_persyaratan'))->filter();
                                    return [
                                        Select::make('nama_persyaratan')
                                            ->options(function () use ($get) {
                                                $data = Persyaratan::whereIn('perizinan_id', function ($query) use ($get) {
                                                    $query->select('perizinan_id')
                                                        ->from('perizinans')
                                                        ->where('perizinan_id', $get('perizinan_id'));
                                                })->pluck('nama_persyaratan', 'id');
                                                return $data;
                                            })
                                            ->disableOptionWhen(function ($value, $state, Get $get) use ($selectedOptions) {
                                                return $selectedOptions->contains($value);
                                            })
                                            ->live()
                                            ->preload(),
                                        Forms\Components\FileUpload::make('file')
                                            ->required()
                                            ->openable()
                                            ->appendFiles()
                                            ->directory('berkas'),
                                    ];
                                })->columns(2),
                        ]),
                    Wizard\Step::make('Formulir')
                        ->schema(function (Get $get): array {
                            $options = Formulir::whereIn('perizinan_id', function ($query) use ($get) {
                                $query->select('perizinan_id')
                                    ->from('formulirs')
                                    ->where('perizinan_id', $get('perizinan_id'));
                            })->get();

                            $selectOptions = [];
                            foreach ($options as $key => $option) {
                                if ($option->type == 'string') {
                                    $selectOptions[$option->nama_formulir] = Forms\Components\TextInput::make('formulir.' . $option->nama_formulir);
                                } else if ($option->type == 'date') {
                                    $selectOptions[$option->nama_formulir] = Forms\Components\DatePicker::make('formulir.' . $option->nama_formulir);
                                }
                            }
                            return [
                                ...$selectOptions
                            ];
                        })->columns(2),
                ])->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('perizinan.nama_perizinan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
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
                Tables\Actions\EditAction::make()
                ->disabled(fn ($record) => in_array($record->status, ['pending'])),
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
            'index' => Pages\ListPermohonans::route('/'),
            'create' => Pages\CreatePermohonan::route('/create'),
            'view' => Pages\ViewPermohonan::route('/{record}'),
            'edit' => Pages\EditPermohonan::route('/{record}/edit'),
        ];
    }

}
