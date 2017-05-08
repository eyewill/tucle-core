
use Eyewill\TucleCore\Eloquent\Batch;
use Eyewill\TucleCore\Eloquent\Nullable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
*/
class User extends Authenticatable {

  use Nullable;
  use Batch;

  /**
  */
  protected $fillable = [
    'user_name',
    'email',
    'password',
    'role',
    'remember_token',
    'disabled',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
  */
  protected $nullable = [
    'remember_token',
  ];

  /**
  */
  public function __toString() {
    return $this->id;
  }

  /**
  * @param $value
  */
  public function getTitleAttribute($value)
  {
    return $this->attributes['user_name'];
  }

  /**
  */
  public function route()
  {
    return route('user.index');
  }

  public function setPasswordAttribute($value)
  {
    if (empty($value))
    {
    unset($this->attributes['password']);
    }
    else
    {
    $this->attributes['password'] = bcrypt($value);
    }
  }

  public function active()
  {
    return !$this->disabled;
  }
}