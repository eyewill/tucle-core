<?php namespace Eyewill\TucleCore\Listeners;

class LoggedOut
{
  public function handle()
  {
    $user = auth()->user();
    if ($user)
      eventlog($user, 'user.logout', $user->title);
  }
}
