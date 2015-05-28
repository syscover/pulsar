<?php namespace Syscover\Pulsar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\ModelTrait;

class EmailAccount extends Model {

    use ModelTrait;

	protected $table        = '001_013_email_account';
    protected $primaryKey   = 'id_013';
    public $timestamps      = false;
    protected $fillable     = ['id_013', 'name_013', 'email_013', 'reply_to_013', 'outgoing_server_013', 'outgoing_user_013', 'outgoing_pass_013', 'outgoing_port_013', 'outgoing_secure_013', 'incoming_server_013', 'incoming_user_013', 'incoming_pass_013', 'incoming_port_013', 'incoming_secure_013', 'incoming_type_013', 'n_emails_013', 'last_check_uid_013'];
    private static $rules   = [
        'name'              => 'required|between:2,100',
        'email'             => 'required|email|between:2,100',
        'replyTo'           => 'email|between:2,100',
        'outgoingServer'    => 'required|between:2,100',
        'outgoingUser'      => 'required|between:2,100',
        'outgoingPass'      => 'required|between:2,100',
        'outgoingPort'      => 'integer|digits_between:1,4',

        'incomingType'      => 'required',
        'incomingServer'    => 'required|between:2,100',
        'incomingUser'      => 'required|between:2,100',
        'incomingPass'      => 'required|between:2,100',
        'incomingPort'      => 'integer|digits_between:1,4',
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['outgoingPassRule']) && $specialRules['outgoingPassRule'])   static::$rules['outgoingPass'] = '';
        if(isset($specialRules['incomingPassRule']) && $specialRules['incomingPassRule'])   static::$rules['incomingPass'] = '';

        return Validator::make($data, static::$rules);
	}
}