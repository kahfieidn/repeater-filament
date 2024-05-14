<?php

namespace App\Filament\Resources\PermohonanResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PermohonanResource;

class EditPermohonan extends EditRecord
{
    protected static string $resource = PermohonanResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
