@if ($presenter->hasRoute('show'))
  @if ($entry instanceof \Eyewill\TucleCore\Contracts\Eloquent\ExpirableInterface)
    @if ($entry->published())
      <a href="{{ $entry->route('show', $entry) }}" class="btn btn-primary btn-sm" target="_blank" title="サイトを開く">
        <i class="fa fa-external-link"></i>
        <span class="hidden-xs">サイトを開く</span>
      </a>
    @else
      @if ($presenter->hasRoute('preview'))
      <a href="{{ $presenter->route('preview', $entry) }}" class="btn btn-primary btn-sm" target="_blank" title="プレビュー">
        <i class="fa fa-external-link"></i>
        <span class="hidden-xs">プレビュー</span>
      </a>
      @endif
    @endif
  @else
    <a href="{{ $entry->route('show', $entry) }}" class="btn btn-primary btn-sm" target="_blank" title="サイトを開く">
      <i class="fa fa-external-link"></i>
      <span class="hidden-xs">サイトを開く</span>
    </a>
  @endif
@endif