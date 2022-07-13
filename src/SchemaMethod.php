<?php

namespace MylesDuncanKing\SimpleMigration;

class SchemaMethod
{
    public static function get(string $tableName, array $columns): array
    {
        if (strpos($tableName, ':') !== false) {
            list($method, $tableName) = explode(':', $tableName);

            if (in_array($method, ['create', 'update'])) {
                return [$method, $tableName];
            }
        }

        $method = array_key_exists('id', $columns) || array_key_exists('uuid', $columns) ? 'create' : 'update';

        return [$method, $tableName];
    }
}
