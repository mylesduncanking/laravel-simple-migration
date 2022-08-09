<?php

namespace MylesDuncanKing\SimpleMigration\Helpers;

class AutoIndex
{
    public static function apply(array $columns): array
    {
        $autoIndexTerms = config('simplemigration.auto_index', []);

        // Ensure terms are an array and have been defined
        if (!is_array($autoIndexTerms) || empty($autoIndexTerms)) {
            return $columns;
        }

        foreach ($columns as $typeName => $modifiers) {
            // Ensure noIndex modifier hasn't been passed
            if (in_array('noIndex', $modifiers)) {
                unset($columns[$typeName][array_search('noIndex', $columns[$typeName])]);
                continue;
            }

            // Get the column name from type name combination
            $column = MethodArgs::get($typeName, 'string')[1][0] ?? '';

            foreach ($autoIndexTerms as $autoIndexTerm) {
                // Ensure column should be auto-indexed
                if (! preg_match('/' . $autoIndexTerm . '/', $column)) {
                    continue;
                }

                // Add index modifier
                $columns[$typeName][] = 'index';
            }
        }

        return $columns;
    }
}
