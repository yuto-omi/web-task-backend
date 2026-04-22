<?php

namespace App\Modules\User\Listeners;

use App\Modules\User\Events\UserLoggedIn;
use App\Modules\User\Events\UserLoggedOut;
use App\Modules\User\Events\UserRegistered;

class UserAuthEventListener
{
    public function handle(UserRegistered|UserLoggedIn|UserLoggedOut $event): void {}
}
