<?php

namespace MylesDuncanKing\SimpleMigration;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use MylesDuncanKing\SimpleMigration\Helpers\AutoAfter;
use MylesDuncanKing\SimpleMigration\Helpers\MethodArgs;
use MylesDuncanKing\SimpleMigration\Helpers\SchemaMethod;

class SimpleMigration extends Migration
{
    public function up(): void
    {
        foreach ($this->migration as $tableName => $columns) {
            $columns = $this->standardiseColumns($columns);

            list($schemaMethod, $tableName) = SchemaMethod::get($tableName, $columns);

            if ($schemaMethod == 'table' && config('simplemigration.auto_after', true)) {
                $columns = AutoAfter::apply($columns);
            }

            Schema::$schemaMethod($tableName, function (Blueprint $table) use ($columns) {
                foreach ($columns as $typeName => $modifiers) {
                    list($type, $args) = MethodArgs::get($typeName, 'string');
                    $column = call_user_func_array([$table, $type], $args);

                    foreach ($modifiers as $modifier) {
                        list($method, $args) = MethodArgs::get($modifier);
                        call_user_func_array([$column, $method], $args);
                    }
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->migration as $tableName => $columns) {
            $columns = $this->standardiseColumns($columns);

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

    private function standardiseColumns(array $columns): array
    {
        $standardised = [];

        foreach ($columns as $key => $value) {
            if (is_numeric(substr($key, 0, 1))) {
                $key = $value;
                $value = [];
            }

            $standardised[$key] = $value;
        }

        return $standardised;
    }
}
