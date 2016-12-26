<?php namespace Eyewill\TucleCore\Contracts\Forms;

use Eyewill\TucleCore\Http\Presenters\ModelPresenter;

interface Factory
{
  public function make(ModelPresenter $presenter);
  public function getDefaultValue(ModelPresenter $presenter, $model = null);
  public function getAttributeNames();
  public function getName();
  public function getRequired();
  public function getLabel();
  public function getHelp();
  public function getAttributes();
  public function getClass();
  public function isPosition($position);

}