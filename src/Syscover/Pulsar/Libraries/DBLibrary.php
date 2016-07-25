<?php namespace Syscover\Pulsar\Libraries;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class DBLibrary
{
    public static function renameColumnWithForeignKey(
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
        $onUpdate = 'cascade',
        $default = null
    )
    {
        if(Schema::hasColumn($tableName, $oldColumnName))
        {
            // foreign key query
            $foreignKeys = DB::select(
                DB::raw('SELECT TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME FROM 
                  INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                  WHERE
                  REFERENCED_TABLE_SCHEMA = \'' . env('DB_DATABASE') . '\' AND
                  COLUMN_NAME = \'' . $oldColumnName . '\' AND
                  TABLE_NAME = \'' . $tableName . '\';' 
                )
            );
            
            if(! empty($foreignKeys))
            {
                if(count($foreignKeys) === 1)
                {
                    $oldForeignKey = $foreignKeys[0]->CONSTRAINT_NAME;

                    Schema::table($tableName, function (Blueprint $table) use ($oldForeignKey) {
                        $table->dropForeign($oldForeignKey);
                    });
                }
                else
                {
                    throw new \Exception('Multiple foreign keys on table ' . $tableName . ' with column ' . $oldColumnName);
                    exit;
                }
            }
            
            // index key query
            $keys = DB::select(DB::raw('SHOW KEYS FROM ' . $tableName . ' WHERE Column_name LIKE \'' . $oldColumnName . '\''));

            if(! empty($keys))
            {
                foreach($keys as $index => $key)
                {
                    if($key->Key_name == 'PRIMARY' || strpos($key->Key_name, 'pk') === false)
                        unset($keys[$index]);
                }
                $keys = array_values($keys);
                
                if(count($keys) === 1)
                {
                    $oldIndexKey = $keys[0]->Key_name;
                    
                    Schema::table($tableName, function (Blueprint $table) use ($oldIndexKey) {
                        $table->dropIndex($oldIndexKey);
                    });
                }
                elseif(count($keys) > 1)
                {
                    throw new \Exception('Multiple keys on table ' . $tableName . ' with column ' . $oldColumnName);
                    exit;
                }
            }
            
            Schema::table($tableName, function (Blueprint $table) use
            ($tableName, $oldColumnName, $newColumnName, $type, $length, $unsigned, $nullable, $foreignKeyName, $referenceTable, $referenceId, $onDelete, $onUpdate, $default)
            {
                $sql = DBLibrary::getAlterTableSql($tableName, $oldColumnName, $newColumnName, $type, $length, $unsigned, $nullable, $default);

                DB::select(DB::raw($sql));

                $table->foreign($newColumnName, $foreignKeyName)
                    ->references($referenceId)
                    ->on($referenceTable)
                    ->onDelete($onDelete)
                    ->onUpdate($onUpdate);
            });
        }
    }

    public static function renameColumn(
        $tableName,
        $oldColumnName,
        $newColumnName,
        $type,
        $length,
        $unsigned,
        $nullable,
        $default = null
    )
    {
        if(Schema::hasColumn($tableName, $oldColumnName))
        {
            Schema::table($tableName, function (Blueprint $table) use
            ($tableName, $oldColumnName, $newColumnName, $type, $length, $unsigned, $nullable, $default)
            {
                $sql = DBLibrary::getAlterTableSql($tableName, $oldColumnName, $newColumnName, $type, $length, $unsigned, $nullable, $default);

                DB::select(DB::raw($sql));
            });
        }
    }
    
    private static function getAlterTableSql($tableName, $oldColumnName, $newColumnName, $type, $length, $unsigned, $nullable, $default = null)
    {
        $sql = null;
        switch ($type) {
            case 'TINYINT':
                $sql = 'ALTER TABLE ' . $tableName . ' CHANGE ' . $oldColumnName . ' '. $newColumnName .' TINYINT(' . $length . ') ' . ($unsigned? 'UNSIGNED ' : null) . ($nullable? 'NULL' : 'NOT NULL') . (empty($default)? null : ' DEFAULT \'' . $default . '\'');
                break;

            case 'SMALLINT':
                $sql = 'ALTER TABLE ' . $tableName . ' CHANGE ' . $oldColumnName . ' '. $newColumnName .' SMALLINT(' . $length . ') ' . ($unsigned? 'UNSIGNED ' : null) . ($nullable? 'NULL' : 'NOT NULL') . (empty($default)? null : ' DEFAULT \'' . $default . '\'');
                break;

            case 'INT':
                $sql = 'ALTER TABLE ' . $tableName . ' CHANGE ' . $oldColumnName . ' '. $newColumnName .' INT(' . $length . ') ' . ($unsigned? 'UNSIGNED ' : null) . ($nullable? 'NULL' : 'NOT NULL') . (empty($default)? null : ' DEFAULT \'' . $default . '\'');
                break;

            case 'BIGINT':
                $sql = 'ALTER TABLE ' . $tableName . ' CHANGE ' . $oldColumnName . ' '. $newColumnName .' BIGINT(' . $length . ') ' . ($unsigned? 'UNSIGNED ' : null) . ($nullable? 'NULL' : 'NOT NULL') . (empty($default)? null : ' DEFAULT \'' . $default . '\'');
                break;

            case 'DECIMAL':
                $sql = 'ALTER TABLE ' . $tableName . ' CHANGE ' . $oldColumnName . ' '. $newColumnName .' DECIMAL(' . $length . ') ' . ($unsigned? 'UNSIGNED ' : null) . ($nullable? 'NULL' : 'NOT NULL') . (empty($default)? null : ' DEFAULT \'' . $default . '\'');
                break;

            case 'VARCHAR':
                $sql = 'ALTER TABLE ' . $tableName . ' CHANGE ' . $oldColumnName . ' '. $newColumnName .' VARCHAR(' . $length . ') CHARACTER SET utf8 COLLATE utf8_unicode_ci ' . ($nullable? 'NULL' : 'NOT NULL') . (empty($default)? null : ' DEFAULT \'' . $default . '\'');
                break;
        }

        return $sql;
    }
}