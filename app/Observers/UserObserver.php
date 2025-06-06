<?php

namespace App\Observers;

use App\Mail\FirstEmail;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // // $recipient = auth()->user();
        // $recipient = User::all();
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Mail::to(auth()->user())->queue(new FirstEmail('Mensagem do email'));

        // Notification::make()
        //     ->title('Usuário Editado')
        //     ->sendToDatabase(auth()->user(), isEventDispatched: true);
            
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
