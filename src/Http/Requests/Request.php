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
    if (!$this->isJson())
      $this->merge(array_map([$this, 'trim'], $this->input()));
    return $this->all();
  }

  protected function trim($input)
  {
    if (is_array($input)) // checkboxes or batch request
    {
      return array_filter($input, 'strlen');
    }
    return trim($input);
  }

  protected function getValidatorInstance()
  {
    $validator = parent::getValidatorInstance();
    if (!is_null($this->presenter))
    {
      $presenter = app()->make($this->presenter);
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
