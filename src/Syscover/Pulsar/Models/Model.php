<?php namespace Syscover\Pulsar\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Syscover\Pulsar\Traits\TraitModel;

/**
 * Class Model
 * Base model
 *
 * @package Syscover\Pulsar\Models
 */

abstract class Model extends BaseModel
{
    use TraitModel;
}