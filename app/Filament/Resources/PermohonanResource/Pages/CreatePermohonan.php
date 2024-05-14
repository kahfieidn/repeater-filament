<?php

namespace App\Filament\Resources\PermohonanResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use App\Models\Permohonan;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Actions\Concerns\HasWizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PermohonanResource;

class CreatePermohonan extends CreateRecord
{

    use HasWizard;
    protected static string $resource = PermohonanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }


    // protected function afterCreate(): void
    // {
    //     dd('afterCreate');
    // }

}
