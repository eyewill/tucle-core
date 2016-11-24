<?php namespace Eyewill\TucleCore\Factories;

use Eyewill\TucleCore\Form\FormGroup;
use Eyewill\TucleCore\Form\FormImage;
use Eyewill\TucleCore\Form\FormInput;
use Eyewill\TucleCore\Form\FormPublished;
use Eyewill\TucleCore\Form\FormSelect;
use Eyewill\TucleCore\Form\FormText;
use Eyewill\TucleCore\Form\FormTextarea;
use Eyewill\TucleCore\FormSpecs\FormSpecGroup;
use Eyewill\TucleCore\FormSpecs\FormSpecPublished;
use Eyewill\TucleCore\FormSpecs\FormSpecSelect;
use Eyewill\TucleCore\FormSpecs\FormSpecText;
use Eyewill\TucleCore\FormSpecs\FormSpecTextarea;
use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

class FormInputFactory
{
  /**
   * @param ModelPresenter $presenter
   * @param $spec
   * @return FormInput
   */
  public static function make(ModelPresenter $presenter, $spec)
  {
    $type = array_get($spec, 'type', 'text');

    switch ($type)
    {
      case 'group':
        $form = new FormGroup($presenter, new FormSpecGroup($spec));
        break;
      case 'published':
        $form = new FormPublished($presenter, new FormSpecPublished($spec));
        break;
      case 'select':
        $form = new FormSelect($presenter, new FormSpecSelect($spec));
        break;
      case 'textarea':
        $form = new FormTextarea($presenter, new FormSpecTextarea($spec));
        break;
      case 'image':
        $form = new FormImage($presenter, new FormSpecTextarea($spec));
        break;
      case 'text':
      default:
        $form = new FormText($presenter, new FormSpecText($spec));
        break;
    }

    return $form;
  }
}