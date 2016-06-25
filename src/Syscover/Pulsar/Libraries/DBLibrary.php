<?php namespace Syscover\Pulsar\Libraries;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class DBLibrary
{
    public static function changeColumnNameWithForeignKey(
        $tableName, 
        $oldColumnName, 
        $newColumnName,
        $type,
        $length,
        $unsigned,
        $nullable,
        $foreignKeyName,
        $referenceTable,
        $referenceId,
        $onDelete = 'restrict',
        $onUpdate = 'cascade'
    )
    {
        if(Schema::hasColumn($tableName, $oldColumnName))
        {
            $keys = DB::select(DB::raw('SHOW KEYS FROM ' . $tableName . ' WHERE Column_name LIKE \'' . $oldColumnName . '\''));
            
            if(! empty($keys))
            {
                if(count($keys) === 1)
                {
                    $oldForeignKey = $keys[0]->Key_name;
                    
                    Schema::table($tableName, function (Blueprint $table) use ($oldForeignKey) {
                        $table->dropForeign($oldForeignKey);
                        $table->dropIndex($oldForeignKey);
                    });
                }
                else
                {
                    exit(0);
                }
            }
            
            Schema::table($tableName, function (Blueprint $table) use
                ($tableName, $oldColumnName, $newColumnName, $type, $length, $unsigned, $nullable, $foreignKeyName, $referenceTable, $referenceId, $onDelete, $onUpdate)
            {

                switch ($type) {
                    case 'INT':
                        $sql = 'ALTER TABLE ' . $tableName . ' CHANGE ' . $oldColumnName . ' '. $newColumnName .' INT(' . $length . ') ' . ($unsigned? 'UNSIGNED ' : null) . ($nullable? 'NULL' : 'NOT NULL');
                        break;
                    case 'VARCHAR':
                        $sql = 'ALTER TABLE ' . $tableName . ' CHANGE ' . $oldColumnName . ' '. $newColumnName .' VARCHAR(' . $length . ') CHARACTER SET utf8 COLLATE utf8_unicode_ci ' . ($nullable? 'NULL' : 'NOT NULL');
                        break;
                }

                DB::select(DB::raw($sql));

                $table->foreign($newColumnName, $foreignKeyName)
                    ->references($referenceId)
                    ->on($referenceTable)
                    ->onDelete($onDelete)
                    ->onUpdate($onUpdate);
            });
        }
    }
}