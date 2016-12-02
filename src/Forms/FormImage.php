<?php namespace Eyewill\TucleCore\Forms;

use Eyewill\TucleCore\FormSpecs\FormSpecImage;

/**
 * Class FormImage
 * @package Eyewill\TucleCore\Forms
 * @property FormSpecImage $spec
 */
class FormImage extends FormInput
{
  public function render($model = null)
  {
    $spec = $this->spec;
    $name = $spec->getName();
    $attributes = $spec->getAttributes()->get();

    $html = '';
    $html.= $this->label();
    if (!is_null($model) && $model->{$name}->originalFilename())
    {
      $html.= $this->renderFileExists($name, $model, $attributes);
    }
    $html.= $this->renderFile($name, $attributes);
    $html.= $this->renderHelp();
    $html.= $this->renderError();

    if ($spec->getGroup())
    {
      $html = $this->grouping($html);
    }

    return $html;
  }

  protected function renderFileExists($name, $model, $attributes)
  {
    $html = '';
    $url = $model->{$name}->url();
    $deleteUrl = $model->route().'/'.$model->id.'/'.$name;
    $filename = $model->{$name}->getOriginalFilename();
    $token = csrf_token();
    $html.= $this->presenter->getForm()->file($name.'_uploaded', $attributes)->toHtml();
    $html.=<<< __SCRIPT__
<script>
    $(function(){
      $('[name=${name}_uploaded]').fileinput({
        overwriteInitial: false,
        language: 'ja',
        showClose: false,
        showUpload: false,
        showCaption: false,
        showRemove: false,
        showBrowse: false,
        maxImageWidth: 150,
        maxFileCount: 1,
        resizeImage: true,
        initialPreview: ['$url'],
        initialPreviewAsData: true,
        initialPreviewConfig: [{
          showDrag: false,
          caption: '$filename',
          url: '$deleteUrl',
          key: '$name',
          extra: {
            _token: '$token',
            _method: 'DELETE'
          }
        }]
      }).on('filepredelete', function(e, key) {
        return !confirm("ファイルを削除します。よろしいですか？");
      }).on('filedeleted', function(e, k, xhr, data) {
        json = xhr.responseJSON;
        if (json.status == 'ok') {
          $.notify({
            icon: 'fa fa-check',
            message: json.message
          });
        }
      }).on('filedeleteerror', function (e, data, msg) {
        $.notify({
          icon: 'fa fa-times',
          message: msg
        }, {
          type: 'danger'
        });          
      });
    });
</script>
__SCRIPT__;

    return $html;
  }

  protected function renderFile($name, $attributes)
  {
    $html = '';
    $html.= $this->presenter->getForm()->file($name, $attributes)->toHtml();
    $html.=<<< __SCRIPT__
<script>
    $(function(){
      $('[name=$name]').fileinput({
        language: 'ja',
      //  showClose: false,
        showUpload: false,
        showCaption: false,
        showRemove: false,
        maxImageWidth: 150,
        maxFileCount: 1,
        resizeImage: true
      }).on('fileselect', function (e) {
        $('[name=${name}_uploaded]').prev().prev().hide();
      }).on('fileclear', function (e) {
        $('[name=${name}_uploaded]').prev().prev().show();
      });
    });
</script>
__SCRIPT__;

    return $html;
  }
}

