<?php namespace Eyewill\TucleCore\Eloquent;

use Carbon\Carbon;

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