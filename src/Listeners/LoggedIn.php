<?php namespace Eyewill\TucleCore\Listeners;

class LoggedIn
{
  public function handle()
  {
    $user = auth()->user();
    eventlog($user, 'user.login', $user->title);
  }
}
