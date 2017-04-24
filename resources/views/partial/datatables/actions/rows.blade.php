@if ($entry instanceof \Eyewill\TucleCore\Contracts\Eloquent\ExpirableInterface)
  @if ($entry->candidates())
    <a href="{{ $presenter->route('preview', $entry) }}" class="btn btn-primary btn-sm" target="_blank" title="プレビュー">
      <i class="fa fa-external-link"></i>
      <span class="hidden-xs">プレビュー</span>
    </a>
  @elseif ($entry->published())
    <a href="{{ $entry->url() }}" class="btn btn-primary btn-sm" target="_blank" title="サイト">
      <i class="fa fa-external-link"></i>
      <span class="hidden-xs">サイト</span>
    </a>
  @else
    <button class="btn btn-default btn-sm" title="公開終了" disabled>
      <i class="fa fa-ban"></i>
      <span class="hidden-xs">公開終了</span>
    </button>
  @endif
@else
  <a href="{{ $entry->url() }}" class="btn btn-primary btn-sm" target="_blank" title="サイト">
    <i class="fa fa-external-link"></i>
    <span class="hidden-xs">サイト</span>
  </a>
@endif
