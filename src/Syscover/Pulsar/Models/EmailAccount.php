<?php namespace Syscover\Pulsar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Syscover\Pulsar\Traits\ModelTrait;

class EmailAccount extends Model {

    use ModelTrait;

	protected $table        = '001_013_email_account';
    protected $primaryKey   = 'id_013';
    public $timestamps      = false;
    protected $fillable     = ['id_013', 'name_013', 'email_013', 'reply_to_013', 'host_smtp_013', 'user_smtp_013', 'pass_smtp_013', 'port_smtp_013', 'secure_smtp_013', 'host_inbox_013', 'user_inbox_013', 'pass_inbox_013', 'port_inbox_013', 'secure_inbox_013', 'type_inbox_013', 'n_emails_013', 'last_check_uid_013'];
    private static $rules   = [
        'name'          => 'required|between:2,100',
        'email'         => 'required|email|between:2,100',
        'replyTo'       => 'email|between:2,100',
        'typeInbox'     => 'required',
        'hostInbox'     => 'required|between:2,100',
        'userInbox'     => 'required|between:2,100',
        'passInbox'     => 'required|between:2,100',
        'portInbox'     => 'integer|digits_between:1,4',
        'hostSmtp'      => 'required|between:2,100',
        'userSmtp'      => 'required|between:2,100',
        'passSmtp'      => 'required|between:2,100',
        'portSmtp'      => 'integer|digits_between:1,4',
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['passInboxRule']) && $specialRules['passInboxRule'])     static::$rules['passInbox'] = '';
        if(isset($specialRules['passSmtpRule']) && $specialRules['passSmtpRule'])       static::$rules['passSmtp'] = '';

        return Validator::make($data, static::$rules);
	}
}