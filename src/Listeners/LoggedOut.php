<?php namespace Eyewill\TucleCore\Listeners;

class LoggedOut
{
  public function handle()
  {
    $user = auth()->user();
    if ($user)
      eventlog($user, 'auth.logout', $user->title);
  }
}
