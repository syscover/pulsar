<?php namespace Pulsar\Pulsar\Models;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource
 */

use Illuminate\Database\Eloquent\Model,
    Pulsar\Pulsar\Libraries\Miscellaneous,
    Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract,
    Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    protected $table        = '001_010_user';
    protected $primaryKey   = 'id_010';
    public $timestamps      = true;
    protected $fillable     = array('id_010','lang_010','profile_010','access_010','user_010','password_010','email_010','name_010','surname_010');
    public static $rules    = array(
        'nombre'        => 'required|between:2,50',
        'apellidos'     => 'required|between:2,50',
        'idioma'        => 'not_in:null',
        'perfil'        => 'not_in:null'
    );

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

    public static function validate($data, $passRule = false, $userRule = false, $emailRule = false)
    {
        if ($passRule)
            static::$rules['password'] = 'required|between:4,50|same:password2';
        if ($userRule)
            static::$rules['user'] = 'required|between:2,50|unique:001_010_usuario,user_010';
        if ($emailRule)
            static::$rules['email'] = 'required|between:2,50|email|unique:001_010_usuario,email_010';
        
        return Validator::make($data, static::$rules);
    }

    public static function getUsuariosLimit($aColumns, $nResultados = null, $inicio = null, $orden = null, $tipoOrden = null, $sWhere=null, $sWhereColumns=null, $count=false)
    {
        $query = User::join('001_006_profile', '001_010_user.profile_010', '=', '001_006_profile.id_006')->newQuery();
                
        $query = Miscellaneous::getQueryWhere($aColumns, $query, $sWhere, $sWhereColumns);

        if($count)
        {
            return $query->count();
        }
        else
        {
            if ($nResultados != null)   $query->take($nResultados)->skip($inicio);
            if ($orden != null)         $query->orderBy($orden, $tipoOrden);

            return $query->get();
        }
    }

    public static function deleteUsuarios($ids)
    {
        User::whereIn('id_010', $ids)->delete();
    }
}

/* End of file user.php */
/* Location: ./Pulsar/Pulsar/Models/User.php */