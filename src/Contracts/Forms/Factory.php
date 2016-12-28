<?php namespace Eyewill\TucleCore\Contracts\Forms;

interface Factory
{
  public function getAttributeNames();
  public function getName();
  public function getRequired();
  public function getLabel();
  public function getHelp();
  public function getAttributes();
  public function getClass();
}