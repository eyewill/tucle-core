@if ($entry instanceof \Eyewill\TucleCore\Contracts\Eloquent\ExpirableInterface)
  @if ($entry->candidates())
    <a href="{{ $presenter->route('preview', $entry) }}" class="btn btn-primary btn-sm" target="_blank" title="プレビュー">
      <i class="fa fa-globe fa-lg"></i>
      <span class="hidden-xs">プレビュー</span>
    </a>
  @elseif ($entry->published())
    <a href="{{ $entry->url() }}" class="btn btn-primary btn-sm" target="_blank" title="サイト">
      <i class="fa fa-globe fa-lg"></i>
      <span class="hidden-xs">サイト</span>
    </a>
  @else
    <button class="btn btn-default btn-sm" title="公開終了" disabled>
      <i class="fa fa-ban fa-lg"></i>
      <span class="hidden-xs">公開終了</span>
    </button>
  @endif
@else
  <a href="{{ $entry->url() }}" class="btn btn-primary btn-sm" target="_blank" title="サイト">
    <i class="fa fa-globe fa-lg"></i>
    <span class="hidden-xs">サイト</span>
  </a>
@endif
<a href="{{ $presenter->route('edit', [$entry]) }}" class="btn btn-primary btn-sm">
  <i class="fa fa-edit fa-lg"></i>
  <span class="hidden-xs">編集</span>
</a>
