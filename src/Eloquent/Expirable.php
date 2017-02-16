<?php namespace Eyewill\TucleCore\Eloquent;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class Expirable
 * @package Eyewill\TucleCore\Eloquent
 *
 * @property array $attributes
 */
trait Expirable
{
  /**
   * @return null|Carbon
   */
  public function publishedAt()
  {
    $value = $this->attributes['published_at'];

    if (empty($value))
    {
      return null;
    }

    return Carbon::parse($value);
  }

  /**
   * @return null|Carbon
   */
  public function terminatedAt()
  {
    $value = $this->attributes['terminated_at'];

    if (empty($value))
    {
      return null;
    }

    return Carbon::parse($value);
  }

  /**
   * 公開前
   */
  public function candidates()
  {
    if (!is_null($this->terminatedAt()) && Carbon::now()->gte($this->terminatedAt()))
    {
      return false;
    }

    if (is_null($this->publishedAt()) && is_null($this->terminatedAt()))
    {
      return true;
    }

    return !is_null($this->publishedAt()) && Carbon::now()->lte($this->publishedAt());
  }

  public function scopeCandidates($query)
  {
    $query->whereNotNull($this->getTable().'.published_at');
    $query->where($this->getTable().'.published_at', '>', DB::raw('NOW()'));
  }

  /**
   * 公開中
   */
  public function published()
  {
    // now < published_at
    if (is_null($this->publishedAt()) || Carbon::now()->lt($this->publishedAt()))
    {
      return false;
    }

    // now >= terminated_at
    if (!is_null($this->terminatedAt()) && Carbon::now()->gte($this->terminatedAt()))
    {
      return false;
    }

    return true;
  }

  public function scopePublished($query)
  {
    $query->where(function ($query) {
      $query->whereNotNull($this->getTable().'.published_at');
      $query->where(function ($query) {
        $query->where($this->getTable().'.published_at', '<=', DB::raw('NOW()'));
        $query->where(function ($query) {
          $query->orWhereNull($this->getTable().'.terminated_at');
          $query->orWhere($this->getTable().'.terminated_at', '>', DB::raw('NOW()'));
        });
      });
    });
  }

  /**
   * 公開前＋公開中
   */
  public function effective()
  {
    return ($this->candidates() || $this->published());
  }

  public function scopeEffective($query)
  {
    $query->where(function ($query) {
      $query->orWhere(function ($query) {
        $query->candidates();
      });
      $query->orWhere(function ($query) {
        $query->published();
      });
    });
  }

  /**
   * 公開終了
   *
   * @return bool
   */
  public function terminated()
  {
    // now > terminated_at
    return (!is_null($this->terminatedAt()) && Carbon::now()->gt($this->terminatedAt()));
  }

  public function scopeTerminated($query)
  {
    $query->where($this->getTable().'.terminated_at', '<', DB::raw('NOW()'));
  }
}