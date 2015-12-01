<?php namespace Syscover\Pulsar\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Syscover\Pulsar\Traits\TraitModel;

abstract class Model extends BaseModel
{
    use TraitModel;
}