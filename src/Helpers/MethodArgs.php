<?php

namespace MylesDuncanKing\SimpleMigration\Helpers;

class MethodArgs
{
    private static $nonDefaultable = [
        // Types
        'id',
        'timestamps',
        'timestampsTz',
        'softDeletes',
        'softDeletesTz',
        'temporary',
        'rememberToken',
    ];

    public static function get(string $colonSeperatedString, ?string $defaultMethod = null): array
    {
        if (strpos($colonSeperatedString, ':') === false) {
            $colonSeperatedString = $colonSeperatedString . ':';
        }

        $method = explode(':', $colonSeperatedString)[0];
        $args   = array_filter(explode(',', substr($colonSeperatedString, strlen($method) + 1)));

        if (empty($args)) {
            foreach (config('simplemigration.type_assumptions') as $pattern => $presetMethod) {
                if (preg_match('/' . $pattern . '/', $method)) {
                    $args = [$method];
                    $method = $presetMethod;
                    break;
                }
            }
        }

        if (empty($args) && !is_null($defaultMethod) && !in_array($method, self::$nonDefaultable)) {
            $args = [$method];
            $method = $defaultMethod;
        }

        foreach ($args as &$arg) {
            $arg = trim($arg);

            // Legacy: Arrays were prefixed with "arr:"
            if (substr($arg, 0, 4) == 'arr:') {
                $arg = substr($arg, 4);
            }

            if (strpos($arg, '|') !== false) {
                $arg = explode('|', $arg);
                $arg = array_map('trim', $arg);
            }
        }

        return [$method, $args];
    }
}
