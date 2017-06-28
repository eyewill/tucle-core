<?php namespace Eyewill\TucleCore\Listeners;

class LoggedIn
{
  public function handle()
  {
    $user = auth()->user();
    eventlog($user, 'auth.login', $user->title);
  }
}
