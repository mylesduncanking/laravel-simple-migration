<?php

namespace MylesDuncanKing\SimpleMigration\Helpers;

class AutoAfter
{
    public static function apply(array $columns): array
    {
        $firstColumn = true;
        $prevColumnName = '';

        foreach ($columns as $typeName => $modifiers) {
            $column = MethodArgs::get($typeName, 'string')[1][0];

            if ($firstColumn) {
                $prevColumnName = $column;
                $firstColumn    = false;
                continue;
            }

            $containsAfter = false;
            foreach ($modifiers as $modifier) {
                $modifier = MethodArgs::get($modifier)[0];
                if ($modifier == 'after') {
                    $containsAfter = true;
                }
            }

            if (! $containsAfter) {
                $modifiers[] = 'after:' . $prevColumnName;
                $columns[$typeName] = $modifiers;
            }

            $prevColumnName = $column;
        }

        return $columns;
    }
}
