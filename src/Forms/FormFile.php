<?php namespace Eyewill\TucleCore\Forms;

use Codesleeve\Stapler\Attachment;
use Eyewill\TucleCore\Factories\Forms\FileFactory;

/**
 * Class FormImage
 * @package Eyewill\TucleCore\Forms
 *
 * @property FileFactory $factory
 */
class FormFile extends FormInput
{
  protected function renderComponent($model)
  {
    $spec = $this->factory;
    $name = $spec->getName();
    $attributes = $spec->getAttributes()->get();
    $html = '';
    $html.= <<< __SCRIPT__
<script>
$(function(){
    var teTagBef = '<tr class="file-preview-frame {frameClass}" id="{previewId}" data-fileindex="{fileindex}"' +
      ' data-template="{template}"', teContent = '<td class="kv-file-content"><a class="kv-file-zoom" onclick="return false" href="#" data-trigger="fileinput">\\n';
    \$.fn.fileinputThemes.explorer.previewMarkupTags = {
      tagBefore1: teTagBef + '>' + teContent,
      tagBefore2: teTagBef + ' title="{caption}">' + teContent,
      tagAfter: '</a></td>\\n{footer}</tr>\\n'
    }
    \$.fn.fileinputLocales['ja']['removeLabel'] = 'キャンセル';  
});
</script>
__SCRIPT__;

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
    $deleteUrl = $this->presenter->route('delete_file', $name);
    $filename = $attachment->originalFilename();
    $contentType = $model->{$name}->contentType();
    $preview = json_encode([$url]);
    if (preg_match('/^image/', $contentType))
    {
      $type = 'image';
    }
    elseif (preg_match('/text/', $contentType))
    {
      $type = 'text';
      $text = app()->make('files')->get($attachment->path());
      $encoding = mb_detect_encoding($text, 'SJIS,UTF-8');
      if ($encoding)
      {
        $preview = json_encode([mb_convert_encoding($text, 'UTF-8', $encoding)]);
      }
    }
    else
    {
      $type = 'object';
      $html.= sprintf('<p><a href="%s" target="_blank">アップロード済みファイル</a></p>', $url);
    }
    $token = csrf_token();
    $html.= $this->presenter->getForm()->file($name.'_uploaded', $attributes)->toHtml();
    $html.=<<< __SCRIPT__
<script>
    $(function(){
      $('[name=${name}_uploaded]').fileinput({
        theme: 'explorer',
        overwriteInitial: false,
        language: 'ja',
        showClose: false,
        showUpload: false,
        showCaption: false,
        showRemove: false,
        showBrowse: false,
        maxFileCount: 1,
        initialPreview: $preview,
        initialPreviewAsData: true,
        autoOrientImage: false,
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
        }],
        previewSettings: {
          image: {
            height: '160px',
            'max-width': 'inherit',
            'max-height': 'inherit'
          }
        }
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
        theme: 'explorer',
        language: 'ja',
        showClose: false,
        showUpload: false,
        showCaption: false,
        showRemove: true,
        showCancel: false,
        maxFileCount: 1,
        textEncoding: 'SJIS',
        autoOrientImage: false,
        previewSettings: {
          image: {
            height: '160px',
            'max-width': 'inherit',
            'max-height': 'inherit'
          }
        }
      }).on('fileloaded', function (e) {
        if ($('[name=${name}_uploaded]').length > 0) {
          $('[name=${name}_uploaded]').data().fileinput.\$container.hide(500);
        }
      }).on('fileclear', function (e) {
        if ($('[name=${name}_uploaded]').length > 0) {
          $('[name=${name}_uploaded]').data().fileinput.\$container.show(500);
        }
      });
    });
</script>
__SCRIPT__;

    return $html;
  }
}

