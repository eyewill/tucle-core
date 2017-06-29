<?php namespace Eyewill\TucleCore\Database;

use Faker\Generator;
use File;
use Illuminate\Database\Eloquent\Model;

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

  public function publishes($published_weight = 80, $terminated_weight = 20)
  {
    // 公開日時
    $publishedAt = $this->faker->optional($published_weight)->dateTime;
    // 公開終了日時 公開日時以降
    $terminatedAt = null;
    if (!is_null($publishedAt))
    {
      $terminatedAt = clone $publishedAt;
      $terminatedAt = $this->faker->optional($terminated_weight)->dateTimeBetween($publishedAt, $terminatedAt->modify('1 year'));
    }

    $this->data = array_merge([
      'published_at' => $publishedAt,
      'terminated_at' => $terminatedAt,
    ],$this->data);
  }

  /**
   * @param $name
   * @param int $weight
   * @param string $extension * or jpg or {jpg,png,gif} ...
   */
  public function image($name, $weight = 50, $extension = '*')
  {
    $entries = File::glob(resource_path().'/dummy_images/*.'.$extension);
    $imagePath = $this->faker->optional($weight)->randomElement($entries);
    if ($imagePath)
    {
      $tmpImagePath = '/tmp/'.uniqid().'_'.basename($imagePath);
      File::copy($imagePath, $tmpImagePath);
      $this->data[$name] = $tmpImagePath;
      printf("\e[0J\rtmp image set to %s ", $name);
    }
  }

  public function file($name, $weight = 50, $extension = '*')
  {
    $entries = File::glob(resource_path().'/dummy_files/*.'.$extension);
    $filePath = $this->faker->optional($weight)->randomElement($entries);
    if ($filePath)
    {
      $tmpFilePath = '/tmp/'.uniqid().'_'.basename($filePath);
      File::copy($filePath, $tmpFilePath);
      $this->data[$name] = $tmpFilePath;
      printf("\e[0J\rtmp file set to %s ", $name);
    }
  }
}
