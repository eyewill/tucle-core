<?php namespace Eyewill\TucleCore\Contracts\Filters;

interface Factory
{
  public function getAttributeNames();
  public function getName();
  public function getLabel();
  public function getAttributes();
  public function getClass();
}