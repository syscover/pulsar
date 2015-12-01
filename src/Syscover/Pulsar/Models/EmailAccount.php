<?php namespace Syscover\Pulsar\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\TraitModel;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class EmailAccount
 *
 * Model with properties
 * <br><b>[id, name, mail, reply_to, outgoing_server, outgoing_user, outgoing_pass, outgoing_port, outgoing_secure, incoming_server, incoming_user, incoming_pass, incoming_port, incoming_secure, incoming_type, n_emails, last_check_uid]</b>
 *
 * @package     Syscover\Pulsar\Models
 */

class EmailAccount extends Model
{
    use TraitModel;
    use Eloquence, Mappable;

	protected $table        = '001_013_email_account';
    protected $primaryKey   = 'id_013';
    protected $suffix       = '013';
    public $timestamps      = false;
    protected $fillable     = ['id_013', 'name_013', 'email_013', 'reply_to_013', 'outgoing_server_013', 'outgoing_user_013', 'outgoing_pass_013', 'outgoing_port_013', 'outgoing_secure_013', 'incoming_server_013', 'incoming_user_013', 'incoming_pass_013', 'incoming_port_013', 'incoming_secure_013', 'incoming_type_013', 'n_emails_013', 'last_check_uid_013'];
    protected $maps         = [];
    protected $relationMaps = [];
    private static $rules   = [
        'name'              => 'required|between:2,100',
        'email'             => 'required|email|between:2,100',
        'replyTo'           => 'email|between:2,100',
        'outgoingServer'    => 'required|between:2,100',
        'outgoingUser'      => 'required|between:2,100',
        'outgoingPass'      => 'required|between:2,100',
        'outgoingPort'      => 'integer|digits_between:1,4',
        'incomingType'      => '',
        'incomingServer'    => 'between:2,100',
        'incomingUser'      => 'between:2,100',
        'incomingPass'      => 'between:2,100',
        'incomingPort'      => 'integer|digits_between:1,4',
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['outgoingPassRule']) && $specialRules['outgoingPassRule'])   static::$rules['outgoingPass'] = '';
        if(isset($specialRules['incomingPassRule']) && $specialRules['incomingPassRule'])   static::$rules['incomingPass'] = '';

        return Validator::make($data, static::$rules);
	}
}