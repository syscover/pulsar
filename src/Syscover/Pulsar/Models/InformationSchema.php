<?php namespace Syscover\Pulsar\Models;

/**
 * @package	    Pulsar
 * @author	    Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL
 * @license
 * @link		http://www.syscover.com
 * @since		Version 2.0
 * @filesource
 */

use \Illuminate\Support\Facades\DB;

class InformationSchema {

    public static function getKeyColumnUsage($table)
    {
        return DB::select('select * from INFORMATION_SCHEMA.KEY_COLUMN_USAGE where REFERENCED_TABLE_NAME = :table', ['table' => $table]);
	}

    public static function hasForeignKey($table, $foreignKey)
    {
        $keysColumns = InformationSchema::getKeyColumnUsage($table);

        foreach($keysColumns as $keyColumn)
        {
            if($keyColumn['CONSTRAINT_NAME'] == $foreignKey)
                return true;
        }
        return false;
    }
}