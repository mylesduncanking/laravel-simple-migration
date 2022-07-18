<?php

namespace MylesDuncanKing\SimpleMigration\Helpers;

class SchemaMethod
{
    public static function get(string $tableName, array $columns): array
    {
        if (strpos($tableName, ':') !== false) {
            list($method, $tableName) = explode(':', $tableName);

            if (in_array($method, ['create', 'table'])) {
                return [$method, $tableName];
            }
        }

        $method = 'table';
        foreach (array_keys($columns) as $typeName) {
            $type = MethodArgs::get($typeName, 'string')[0];
            if (in_array($type, config('simplemigration.create_triggers', []))) {
                $method = 'create';
                break;
            }
        }

        return [$method, $tableName];
    }
}
