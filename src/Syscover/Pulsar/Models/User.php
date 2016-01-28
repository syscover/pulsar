<?php namespace Syscover\Pulsar\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class Profile
 *
 * Model with properties
 * <br><b>[id, lang, profile, access, user, password, email, name, surname]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class User extends Authenticatable
{
    use TraitModel;
    use Eloquence, Mappable;

    protected $table        = '001_010_user';
    protected $primaryKey   = 'id_010';
    protected $suffix       = '010';
    public $timestamps      = true;
    protected $fillable     = ['id_010', 'lang_010', 'profile_010', 'access_010', 'user_010', 'password_010', 'email_010', 'name_010', 'surname_010'];
    protected $hidden       = ['password_010', 'remember_token_010'];
    protected $maps         = [];
    protected $relationMaps = [
        'profile'   => \Syscover\Pulsar\Models\Profile::class,
    ];
    private static $rules    = [
        'name'      => 'required|between:2,50',
        'surname'   => 'required|between:2,50',
        'email'     => 'required|between:2,50|email|unique:001_010_user,email_010',
        'lang'      => 'not_in:null',
        'profile'   => 'not_in:null',
        'user'      => 'required|between:2,50|unique:001_010_user,user_010',
        'password'  => 'required|between:4,50|same:repassword'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['emailRule']) && $specialRules['emailRule']) static::$rules['email'] = 'required|between:2,50|email';
        if(isset($specialRules['userRule']) && $specialRules['userRule']) static::$rules['user'] = 'required|between:2,50';
        if(isset($specialRules['passRule']) && $specialRules['passRule']) static::$rules['password'] = '';

        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder($query)
    {
        return $query->join('001_006_profile', '001_010_user.profile_010', '=', '001_006_profile.id_006');
    }

    /**
     * Get profile from user
     *
     * @return \Syscover\Pulsar\Models\Profile
     */
    public function getProfile()
    {
        return $this->belongsTo(Profile::class, 'profile_010');
    }


    protected static function addToGetIndexRecords($parameters)
    {
        return User::builder();
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_010;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->{$this->getRememberTokenName()};
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token_010';
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email_010;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->user_010;
    }
}