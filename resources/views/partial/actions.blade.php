<a href="{{ $entry->url() }}" class="btn btn-primary btn-sm" target="_blank" title="ウェブサイトを表示">
  <i class="fa fa-globe fa-lg"></i>
  <span class="hidden-xs">ウェブサイトを表示</span>
</a>
<a href="{{ $presenter->route('edit', [$entry]) }}" class="btn btn-primary btn-sm">
  <i class="fa fa-edit fa-lg"></i>
  <span class="hidden-xs">編集</span>
</a>
