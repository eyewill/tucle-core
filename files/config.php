<?php

return [

  'brand' => 'TUCLE5',

  'modules' => [],

  'front_url' => env('FRONT_URL', 'http://localhost'),

  'roles' => [
    [
      'name' => 'admin',
      'label' => 'システム管理者',
      'default_url' => '/',
    ],
    [
      'name' => 'user',
      'label' => '一般ユーザー',
      'default_url' => '/',
    ],
  ],

  'event_log' => [
    'enabled' => true,
    'user_credential_key' => 'email',
  ],
];
