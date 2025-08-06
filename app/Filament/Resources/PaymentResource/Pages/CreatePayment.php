<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Filament\Traits\HandlesRecurrences;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;
use App\Models\Payment;

class CreatePayment extends CreateRecord
{
    use HandlesRecurrences;
    
    protected static string $resource = PaymentResource::class;

}
