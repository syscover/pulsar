<?php namespace Syscover\Pulsar\Libraries;

/**
 * @package		Pulsar
 * @author		Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2014, SYSCOVER, SL.
 * @license
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource  Librarie that instance helper functions
 */

use Illuminate\Validation\Validator;

class CustomValidator extends Validator
{
    public function validateDigit($attribute, $value, $parameters)
    {
        return (strlen($value) == $parameters[0])? true : false;
    }

    public function validateCronExpression($attribute, $value, $parameters)
    {
        try
        {
            \Cron\CronExpression::factory($value);
            return true;
        }
        catch (\InvalidArgumentException $e)
        {
            return false;
        }  
    }
}