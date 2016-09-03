<?php namespace Syscover\Pulsar\Models;

use Syscover\Pulsar\Core\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

/**
 * Class User
 *
 * Model with properties
 * <br><b>[id, lang_id, profile_id, access, user, password, email, name, surname]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use Eloquence, Mappable;
    use Notifiable;

    protected $table        = '001_010_user';
    protected $primaryKey   = 'id_010';
    protected $suffix       = '010';
    public $timestamps      = true;
    protected $fillable     = ['id_010', 'lang_id_010', 'profile_id_010', 'access_010', 'user_010', 'password_010', 'email_010', 'name_010', 'surname_010'];
    protected $hidden       = ['password_010', 'remember_token_010'];
    protected $maps         = [];
    protected $relationMaps = [
        'profile'   => Profile::class,
    ];
    private static $rules    = [
        'name'      => 'required|between:2,255',
        'surname'   => 'required|between:2,255',
        'email'     => 'required|between:2,255|email|unique:001_010_user,email_010',
        'lang'      => 'not_in:null',
        'profile'   => 'not_in:null',
        'user'      => 'required|between:2,255|unique:001_010_user,user_010',
        'password'  => 'required|between:4,50|same:repassword'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['emailRule']) && $specialRules['emailRule']) static::$rules['email'] = 'required|between:2,255|email';
        if(isset($specialRules['userRule']) && $specialRules['userRule'])   static::$rules['user'] = 'required|between:2,255';
        if(isset($specialRules['passRule']) && $specialRules['passRule'])   static::$rules['password'] = '';

        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder($query)
    {
        return $query->join('001_006_profile', '001_010_user.profile_id_010', '=', '001_006_profile.id_006');
    }

    /**
     * Get profile from user
     *
     * @return \Syscover\Pulsar\Models\Profile
     */
    public function getProfile()
    {
        return $this->belongsTo(Profile::class, 'profile_id_010');
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
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
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