<?php

namespace App\Filament\Company\Resources\Attendances\Pages;

use App\Filament\Company\Resources\Attendances\AttendanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;
}
