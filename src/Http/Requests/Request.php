<?php

namespace Eyewill\TucleCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

abstract class Request extends FormRequest
{
  protected $errorMessage = '入力内容をご確認ください';

  protected $presenter;

  /**
   * 認証状態を返す
   * @return bool
   */
  public function authorize()
  {
    return auth()->check();
  }

  protected function validationData()
  {
    $this->merge(array_map('trim', $this->input()));
    return $this->all();
  }

  protected function getValidatorInstance()
  {
    $validator = parent::getValidatorInstance();
    if (!is_null($this->presenter))
    {
      $presenter = app()->make($this->presenter);
      debug($presenter->getAttributeNames());
      $validator->setAttributeNames($presenter->getAttributeNames());
    }
    return $validator;
  }

  public function response(array $errors)
  {
    $response = parent::response($errors);
    if ($response instanceof JsonResponse)
    {
      return $response;
    }

    return parent::response($errors)
      ->with('error', $this->errorMessage);
  }
}
