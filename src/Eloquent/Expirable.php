<?php namespace Eyewill\TucleCore\Eloquent;
use Carbon\Carbon;

/**
 * Class Expirable
 * @package Eyewill\TucleCore\Eloquent
 *
 * @property array $attributes
 * @property Carbon|null $published_at
 * @property Carbon|null $terminated_at
 */
trait Expirable
{
  public function getPublishedAtAttribute($value)
  {
    if (is_null($value))
      return null;

    return Carbon::parse($value);
  }

  public function getTerminatedAtAttribute($value)
  {
    if (is_null($value))
      return null;

    return Carbon::parse($value);
  }

  public function setPublishedAtAttribute($value)
  {
    if (empty($value))
      $this->attributes['published_at'] = null;
    else
      $this->attributes['published_at'] = Carbon::parse($value)->toDateTimeString();
  }

  public function setTerminatedAtAttribute($value)
  {
    if (empty($value))
      $this->attributes['terminated_at'] = null;
    else
      $this->attributes['terminated_at'] = Carbon::parse($value)->toDateTimeString();
  }

  /**
   * 公開前
   */
  public function candidates()
  {
    if (isset($this->terminated_at) && Carbon::now()->gte($this->terminated_at))
    {
      return false;
    }

    if (is_null($this->published_at) && is_null($this->terminated_at))
    {
      return true;
    }

    return isset($this->published_at) && Carbon::now()->lte($this->published_at);
  }

  public function scopeCandidates($query)
  {

  }

  /**
   * 公開中
   */
  public function published()
  {
    // now < published_at
    if (is_null($this->published_at) || Carbon::now()->lt($this->published_at))
    {
      return false;
    }

    // now >= terminated_at
    if (isset($this->terminated_at) && Carbon::now()->gte($this->terminated_at))
    {
      return false;
    }

    return true;
  }

  public function scopePublished($query)
  {

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
  }
}