<?php namespace Eyewill\TucleCore\Listeners;

use App\User;
use Illuminate\Auth\Events\Login;

class LoggedIn
{
  public function handle(Login $login)
  {
    if ($login->user instanceof User)
    {
      eventlog($login->user, 'auth.login', $login->user->title);
    }
  }
}
