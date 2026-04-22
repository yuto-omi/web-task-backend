<?php

namespace App\Modules\User\Listeners;

use App\Modules\User\Events\UserRegistered;
use App\Modules\User\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    public function handle(UserRegistered $event): void
    {
        Mail::to($event->user->email)->send(new WelcomeMail($event->user));
    }
}
