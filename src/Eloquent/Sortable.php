<?php namespace Eyewill\TucleCore\Eloquent;

trait Sortable
{
  public function sortOrder($order = -1)
  {
    $id = $this->id;
    $entries = static::query()
      ->where('id', '<>', $id)
      ->orderBy('order', 'asc')
      ->orderBy('id', 'desc')
      ->get(['id', 'order']);

    $sortedValues = [];
    $inserted = false;
    foreach($entries as $entry)
    {
      if ($entry->order == $order)
      {
        $sortedValues[] = $id;
        $inserted = true;
      }
      $sortedValues[] = $entry->id;
    }

    if (!$inserted)
    {
      $sortedValues[] = $id;
    }

    // 重複を削除して、indexを振り直す
    $sortedValues = array_values(array_unique($sortedValues));

    $cases = [];
    foreach ($sortedValues as $i => $id)
    {
      $cases[] = sprintf('WHEN id = %d THEN %s', $id, $i+1);
    }

    $sql = "UPDATE `".$this->getTable()."` SET `order` = CASE ".implode(' ', $cases).' END';
    \DB::statement(\DB::raw($sql));
  }
}