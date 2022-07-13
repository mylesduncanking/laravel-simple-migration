<?php

namespace MylesDuncanKing\SimpleMigration;

class MethodArgs
{
    private static $presetMethods = [
        '_id$' => 'foreignId',
        '_at$' => 'timestamp',
    ];

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
            foreach (self::$presetMethods as $pattern => $presetMethod) {
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

            if (substr($arg, 0, 4) == 'arr:') {
                $arg = explode('|', substr($arg, 4));
                $arg = array_map('trim', $arg);
            }
        }

        return [$method, $args];
    }
}
