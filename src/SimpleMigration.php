<?php

namespace MylesDuncanKing\SimpleMigration;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SimpleMigration extends Migration
{
    public function up()
    {
        foreach ($this->migration as $tableName => $columns) {
            $schemaMethod = SchemaMethod::get($tableName, $columns);
        }
        $schemaMethod = property_exists($this, 'table') ? 'table' : 'create';
        $tableName    = $this->$schemaMethod;

        Schema::$schemaMethod($tableName, function (Blueprint $table) {
            foreach ($this->migration as $typeName => $options) {
                list($type, $args) = MethodArgs::get($typeName, 'string');
                $column = call_user_func_array([$table, $type], $args);

                foreach ($options as $option) {
                    list($method, $args) = MethodArgs::get($option);
                    call_user_func_array([$column, $method], $args);
                }
            }
        });
    }

    public function down()
    {
        if (property_exists($this, 'create')) {
            Schema::dropIfExists($this->create);
        } else {
            Schema::table($this->table, function (Blueprint $table) {
                foreach (array_reverse(array_keys($this->migration)) as $typeName) {
                    $column = MethodArgs::get($typeName, 'string')[1][0];

                    if (Schema::hasColumn($this->table, $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
}
