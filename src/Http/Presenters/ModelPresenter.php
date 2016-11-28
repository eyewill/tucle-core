<?php namespace Eyewill\TucleCore\Http\Presenters;

use Collective\Html\FormBuilder;
use Collective\Html\HtmlBuilder;
use Eyewill\TucleCore\Factories\FormInputFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;

class ModelPresenter
{
  protected $pageTitle;
  protected $form;
  protected $html;
  protected $forms = [];
  protected $entryTableColumns = [];
  protected $showColumns = [];
  protected $views = [];
  protected $routes = [];
  protected $breadCrumbs = [];

  public function __construct(FormBuilder $form, HtmlBuilder $html)
  {
    $this->form = $form;
    $this->html = $html;
    $this->breadCrumbs = array_merge([[
      'label' => config('tucle.brand', 'TUCLE5'),
      'url' => '/',
    ]], $this->breadCrumbs);
  }

  public function getForm()
  {
    return $this->form;
  }

  public function getHtml()
  {
    return $this->html;
  }

  public function setPageTitle($pageTitle)
  {
    $this->pageTitle = $pageTitle;
  }

  public function getPageTitle()
  {
    return $this->pageTitle;
  }

  public function renderForm($model = null)
  {
    $html = '';

    foreach ($this->forms as $spec)
    {
      $form = FormInputFactory::make($this, $spec);
      $html.= $form->render();
      $name = array_get($spec, 'name');
      $type = array_get($spec, 'type');
      if ($type == 'image')
      {
        if (!is_null($model))
        {
          $url = $model->{$name}->url();
          $deleteUrl = $model->route().'/'.$model->id.'/'.$name;
          $filename = $model->{$name}->getOriginalFilename();
          $token = csrf_token();
          $html.=<<< __SCRIPT__
<script>
    $(function(){
      $('[name=$name]').fileinput({
        overwriteInitial: true,
        language: 'ja',
        showClose: false,
        showUpload: false,
        showCaption: false,
        showRemove: false,
        maxImageWidth: 150,
        maxFileCount: 1,
        resizeImage: true,
        initialPreview: ['$url'],
        initialPreviewAsData: true,
        initialPreviewConfig: [{
          showDrag: false,
          caption: '$filename',
          url: '$deleteUrl',
          extra: {
            _token: '$token',
            _method: 'DELETE'
          }
        }]
      }).on('filepredelete', function() {
        return !confirm("ファイルを削除します。よろしいですか？");
      }).on('filedeleted', function(e, k, xhr,data) {
        $.notify({
          icon: 'fa fa-check',
          message: xhr.responseJSON.message
        });
      });
    });
</script>
__SCRIPT__;
        }
        else
        {
          $html.=<<< __SCRIPT__
<script>
    $(function(){
      $('[name=$name]').fileinput({
        language: 'ja',
        showClose: false,
        showUpload: false,
        showCaption: false,
        showRemove: false,
        maxImageWidth: 150,
        maxFileCount: 1,
        resizeImage: true
      });
    });
</script>
__SCRIPT__;
        }
      }
    }

    return new HtmlString($html);
  }

  public function entries(Collection $entries)
  {
    $view = array_get($this->views, 'entries', 'tucle::partial.entries');

    return new HtmlString(view()->make($view, [
      'presenter'    => $this,
      'tableColumns' => $this->entryTableColumns,
      'entries'      => $entries,
    ])->render());
  }

  public function entry($column, $entry)
  {
    $name  = array_get($column, 'name');
    $links = array_get($column, 'links', false);

    $html = '';
    $html.= '<td>';
    if ($links)
    {
      $html.= '<a href="'.$this->route('show', [$entry]).'">'.e($entry->{$name}).'</a>';
    }
    else
    {
      $html.= e($entry->{$name});
    }
    $html.= '</td>';

    return new HtmlString($html);
  }

  function routeName($action = null)
  {
    return $this->routes[$action];
  }

  function route($action = null, $parameters = [])
  {
    if (is_array($action))
    {
      $parameters = $action;
      $action = array_shift($parameters);
    }

    return route($this->routes[$action], $parameters);
  }

  public function renderValues($attributes = [])
  {
    $html = '';
    foreach ($attributes as $attribute)
    {
      $type = array_get($attribute, 'type', 'text');

      $html.= $this->renderValue($type, $attribute);
    }

    return $html;
  }


  public function renderValue($type, $attribute = [], $group = true)
  {
    $attributes = array_merge($this->defaults[$type] + $this->default, $attribute);

    $this->elementRenderer->setAttributes($attributes);

    $html = '';
    if ($group)
    {
      $html.= $this->elementRenderer->group();
    }

    $html.= $this->elementRenderer->label();

    $html.= $this->elementRenderer->value();

    if ($group)
    {
      $html.= $this->elementRenderer->groupClose();
    }

    return $html;
  }

  public function renderBreadCrumbs($breadCrumb = null)
  {
    $breadCrumbs = $this->breadCrumbs;
    if (func_num_args() > 1)
    {
      $breadCrumbs = array_merge($breadCrumbs, func_get_args());
    }
    elseif (!is_null($breadCrumb))
    {
      $breadCrumbs[] = $breadCrumb;
    }

    $html = '';
    $html.= '<ol class="breadcrumb">';
    foreach ($breadCrumbs as $crumb)
    {
      $url = false;
      if (array_has($crumb, 'url')) $url = $crumb['url'];
      elseif (array_has($crumb, 'route')) $url = $this->route($crumb['route']);
      if ($url)
        $html.= '<li><a href="'.$url.'">'.$crumb['label'].'</a></li>';
      else
        $html.= '<li>'.$crumb['label'].'</li>';
    }
    $html.= '</ol>';

    return new HtmlString($html);
  }

  public function renderDetails($model)
  {
    $html = '';
    $html.= '<div class="dl-horizontal">';
    foreach ($this->showColumns as $column)
    {
      $html.= '<dt>';
      $html.= array_get($column, 'label');
      $html.= '</dt>';
      $html.= '<dd>';
      $html.= $model->{array_get($column, 'name')};
      $html.= '</dd>';
    }
    $html.= '</div>';
    return new HtmlString($html);
  }

  public function renderPageActions($model, $actions = [])
  {
    $html = '';
    $html.= '<div class="row">';
    $html.= '<div class="col-xs-12">';
    $html.= '<div class="btn-toolbar pull-right">';
    foreach ($actions as $action)
    {
      $method = 'render'.studly_case($action).'PageAction';
      if (method_exists($this, $method))
      {
        $html.= $this->{$method}($model);
      }
    }
    $html.= '</div>';
    $html.= '</div>';
    $html.= '</div>';

    return new HtmlString($html);
  }

  public function renderBackPageAction($model)
  {
    $url = $this->route('index');
    return new HtmlString('<a href="'.$url.'" class="btn btn-default">一覧に戻る</a>');
  }

  public function renderCreatePageAction($model)
  {
    $url = $this->route('create');
    return new HtmlString('<a href="'.$url.'" class="btn btn-primary">作成</a>');
  }

  public function renderEditPageAction($model)
  {
    $url = $this->route('edit', $model);
    return new HtmlString('<a href="'.$url.'" class="btn btn-primary">編集</a>');
  }

  public function renderShowPageAction($model)
  {
    $url = $this->route('show', $model);
    return new HtmlString('<a href="'.$url.'" class="btn btn-primary">表示</a>');
  }
}