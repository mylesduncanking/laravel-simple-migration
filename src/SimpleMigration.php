<?php

namespace MylesDuncanKing\SimpleMigration;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SimpleMigration extends Migration
{
    private $presetMethods = [
        '_id' => 'unsignedBigInteger',
        '_at' => 'timestamp',
    ];

    private $nonDefaultable = [
        'softDeletesTz',
        'softDeletes',
        'timestampsTz',
        'timestamps',
        'id',
        'rememberToken',
    ];

    public function up()
    {
        $schemaMethod = property_exists($this, 'table') ? 'table' : 'create';
        $tableName    = $this->$schemaMethod;

        Schema::$schemaMethod($tableName, function (Blueprint $table) {
            foreach ($this->columns as $typeName => $options) {
                list($type, $args) = $this->getMethodArgs($typeName, 'string');
                $column = call_user_func_array([$table, $type], $args);

                foreach ($options as $option) {
                    list($method, $args) = $this->getMethodArgs($option);
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
                foreach (array_reverse(array_keys($this->columns)) as $typeName) {
                    $column = $this->getMethodArgs($typeName, 'string')[1][0];

                    if (Schema::hasColumn($this->table, $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }

    public function getMethodArgs(string $colonSeperatedString, ?string $defaultMethod = null): array
    {

        die('asd');
        if (strpos($colonSeperatedString, ':') === false) {
            $colonSeperatedString = $colonSeperatedString . ':';
        }

        $method = explode(':', $colonSeperatedString)[0];
        $args   = array_filter(explode(',', substr($colonSeperatedString, strlen($method) + 1)));

        if (empty($args)) {
            foreach ($this->presetMethods as $needle => $presetMethod) {
                if (stripos($method, $needle)) {
                    $args = [$method];
                    $method = $presetMethod;
                    break;
                }
            }
        }

        if (empty($args) && !is_null($defaultMethod) && !in_array($method, $this->nonDefaultable)) {
            $args = [$method];
            $method = $defaultMethod;
        }

        foreach ($args as &$arg) {
            $arg = trim($arg);

            if (substr($arg, 0, 4) == 'arr:') {
                $arg = explode('|', substr($arg, 4));
                $arg = array_map('trim', $arg);
            }
        }

        return [$method, $args];
    }
}
