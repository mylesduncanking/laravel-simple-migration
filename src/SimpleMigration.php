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
            list($schemaMethod, $tableName) = SchemaMethod::get($tableName, $columns);

            Schema::$schemaMethod($tableName, function (Blueprint $table) use ($columns) {
                foreach ($columns as $typeName => $options) {
                    list($type, $args) = MethodArgs::get($typeName, 'string');
                    $column = call_user_func_array([$table, $type], $args);

                    foreach ($options as $option) {
                        list($method, $args) = MethodArgs::get($option);
                        call_user_func_array([$column, $method], $args);
                    }
                }
            });
        }
    }

    public function down()
    {
        foreach ($this->migration as $tableName => $columns) {
            list($schemaMethod, $tableName) = SchemaMethod::get($tableName, $columns);

            if ($schemaMethod == 'create') {
                Schema::dropIfExists($tableName);
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                foreach (array_reverse(array_keys($columns)) as $typeName) {
                    $column = MethodArgs::get($typeName, 'string')[1][0];

                    if (Schema::hasColumn($tableName, $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
}
