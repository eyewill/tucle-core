<?php namespace Eyewill\TucleCore\Forms;

use Codesleeve\Stapler\Attachment;
use Eyewill\TucleCore\FormSpecs\FormSpecFile;
use File;

/**
 * Class FormImage
 * @package Eyewill\TucleCore\Forms
 * @property FormSpecFile $spec
 */
class FormFile extends FormInput
{
  protected function renderComponent($model)
  {
    $spec = $this->spec;
    $name = $spec->getName();
    $attributes = $spec->getAttributes()->get();
    $html = '';
    if (!is_null($model) && $model->{$name}->originalFilename())
    {
      $html.= $this->renderFileExists($name, $model, $attributes);
    }
    $html.= $this->renderFile($name, $attributes);
    return $html;
  }

  protected function renderFileExists($name, $model, $attributes)
  {
    /** @var Attachment $attachment */
    $attachment = $model->{$name};
    $html = '';
    $url = $attachment->url();
    $deleteUrl = $model->route().'/'.$model->id.'/'.$name;
    $filename = $attachment->originalFilename();
    $contentType = $model->{$name}->contentType();
    $preview = json_encode([$url]);
    $attributes = array_merge($attributes, [
      'data-'
    ]);
    if (preg_match('/^image/', $contentType))
    {
      $type = 'image';
    }
    elseif (preg_match('/text/', $contentType))
    {
      $type = 'text';
      $text = File::get($attachment->path());
      $encoding = mb_detect_encoding($text, 'SJIS,UTF-8');
      $preview = json_encode([mb_convert_encoding($text, 'UTF-8', $encoding)]);
    }
    else
    {
      $type = 'object';
    }
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
        initialPreview: $preview,
        initialPreviewAsData: true,
        initialPreviewConfig: [{
          type: '$type',
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
        resizeImage: true,
        textEncoding: 'SJIS',
      }).on('fileselect', function (e) {
        console.log($('[name=${name}_uploaded]'));
        $('[name=${name}_uploaded]').data().fileinput.\$container.hide(1000);
      }).on('fileclear', function (e) {
        $('[name=${name}_uploaded]').data().fileinput.\$container.show(500);
      });
    });
</script>
__SCRIPT__;

    return $html;
  }
}

