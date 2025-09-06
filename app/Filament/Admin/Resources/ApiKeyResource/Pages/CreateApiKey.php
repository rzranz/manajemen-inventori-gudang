<?php

namespace App\Filament\Admin\Resources\ApiKeyResource\Pages;

use App\Filament\Admin\Resources\ApiKeyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateApiKey extends CreateRecord
{
    protected static string $resource = ApiKeyResource::class;
}
