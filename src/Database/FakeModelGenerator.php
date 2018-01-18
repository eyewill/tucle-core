<?php namespace Eyewill\TucleCore\Database;

use Faker\Generator;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FakeModelGenerator
{
  protected $repository = [];
  protected $data = [];
  protected $faker;

  public function __construct()
  {
    $this->faker = \Faker\Factory::create('ja_JP');
  }

  /**
   * @return Generator
   */
  public function faker()
  {
    return $this->faker;
  }

  /**
   * @param $key
   * @param callable|null $callback
   * @return mixed|Collection|array
   */
  public function getRepository($key, callable $callback = null)
  {
    if (!array_key_exists($key, $this->repository))
    {
      if (is_null($callback))
      {
        $collection = app()->make($key);
        if ($collection instanceof Model)
        {
          $value = $collection->all();
        }
        else
        {
          $value = $collection;
        }
      }
      else
      {
        $value = $callback();
      }
      $this->repository[$key] = $value;
    }

    return $this->repository[$key];
  }

  public function getData()
  {
    return $this->data;
  }

  public function setData($data, $value = null)
  {
    if (!is_array($data))
    {
      $this->data[$data] = $value;
    }
    else
    {
      $this->data = $data;
    }
  }

  public function date($name, $weight = 50, $betweenFrom = '-1 year', $betweenTo = '+1 year')
  {
    if ($betweenFrom instanceof \DateTime and $betweenTo instanceof \DateTime)
    {
      $date = $this->faker->optional($weight)->dateTimeBetween($betweenFrom, $betweenTo);
    }
    elseif ($betweenFrom instanceof \DateTime)
    {
      $date = clone $betweenFrom;
      $date = $this->faker->optional($weight)->dateTimeBetween($betweenFrom, $date->modify($betweenTo));
    }
    elseif ($betweenTo instanceof \DateTime)
    {
      $date = clone $betweenTo;
      $date = $this->faker->optional($weight)->dateTimeBetween($date->modify($betweenFrom), $betweenTo);
    }
    else
    {
      $date = $this->faker->optional($weight)->dateTimeBetween($betweenFrom, $betweenTo);
    }
    $this->data[$name] = $date;
    return $date;
  }

  public function publishes($published_weight = 80, $terminated_weight = 20, $betweenFrom = '-1 year', $betweenTo = '+1 year', $terminatedTo = '+1 year')
  {
    $date = $this->date('published_at', $published_weight, $betweenFrom, $betweenTo);
    if (!is_null($date))
    {
      $this->date('terminated_at', $terminated_weight, $date, $terminatedTo);
    }
  }

  /**
   * @param $name
   * @param int $weight
   * @param string $extension * or jpg or {jpg,png,gif} ...
   * @param array|null $paths dummy image file paths if set null use default image paths
   */
  public function image($name, $weight = 50, $extension = '*', $paths = null)
  {
    if (is_null($paths))
    {
      $paths = File::glob(__DIR__.'/../resources/dummy_images/*.'.$extension);
    }
    $imagePath = $this->faker->optional($weight)->randomElement($paths);
    if ($imagePath)
    {
      $tmpImagePath = '/tmp/'.uniqid().'_'.basename($imagePath);
      File::copy($imagePath, $tmpImagePath);
      $this->data[$name] = $tmpImagePath;
      printf("\e[0J\rtmp image set to %s \r", $name);
    }
  }

  /**
   * @param $name
   * @param int $weight
   * @param string $extension * or jpg or {pdf,xlsx,docx} ...
   * @param array|null $paths dummy image file paths if set null use default image paths
   */
  public function file($name, $weight = 50, $extension = '*', $paths = null)
  {
    if (is_null($paths))
    {
      $paths = File::glob(__DIR__.'/../resources/dummy_files/*.'.$extension);
    }
    $filePath = $this->faker->optional($weight)->randomElement($paths);
    if ($filePath)
    {
      $tmpFilePath = '/tmp/'.uniqid().'_'.basename($filePath);
      File::copy($filePath, $tmpFilePath);
      $this->data[$name] = $tmpFilePath;
      printf("\e[0J\rtmp file set to %s \r", $name);
    }
  }
}
