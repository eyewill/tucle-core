<?php namespace Eyewill\TucleCore\Factories;

use Eyewill\TucleCore\Form\FormGroup;
use Eyewill\TucleCore\Form\FormInput;
use Eyewill\TucleCore\Form\FormPublished;
use Eyewill\TucleCore\Form\FormSelect;
use Eyewill\TucleCore\Form\FormText;
use Eyewill\TucleCore\Form\FormTextarea;
use Eyewill\TucleCore\FormTypes\FormTypeGroup;
use Eyewill\TucleCore\FormTypes\FormTypePublished;
use Eyewill\TucleCore\FormTypes\FormTypeSelect;
use Eyewill\TucleCore\FormTypes\FormTypeText;
use Eyewill\TucleCore\FormTypes\FormTypeTextarea;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormInputFactory
{
  /**
   * @param $spec
   * @return FormInput
   */
  public static function make(ModelPresenter $presenter, $spec)
  {
    $type = array_get($spec, 'type', 'text');

    switch ($type)
    {
      case 'group':
        $form = new FormGroup($presenter, new FormTypeGroup($spec));
        break;
      case 'published':
        $form = new FormPublished($presenter, new FormTypePublished($spec));
        break;
      case 'select':
        $form = new FormSelect($presenter, new FormTypeSelect($spec));
        break;
      case 'textarea':
        $form = new FormTextarea($presenter, new FormTypeTextarea($spec));
        break;
      case 'text':
      default:
        $form = new FormText($presenter, new FormTypeText($spec));
        break;
    }

    return $form;
  }
}