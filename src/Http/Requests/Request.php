<?php

namespace Eyewill\TucleCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
  protected $errorMessage = '入力内容をご確認ください';

  /**
   * 認証状態を返す
   * @return bool
   */
  public function authorize()
  {
    return auth()->check();
  }

  public function response(array $errors)
  {
    $this->merge(array_map('trim', $this->all()));

    return parent::response($errors)
      ->with('error', $this->errorMessage);
  }
}
