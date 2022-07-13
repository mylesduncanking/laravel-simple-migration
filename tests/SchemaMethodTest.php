<?php

declare(strict_types=1);

require __DIR__ . '/../src/SchemaMethod.php';

use MylesDuncanKing\SimpleMigration\SchemaMethod;
use PHPUnit\Framework\TestCase;

final class SchemaMethodTest extends TestCase
{
    public function testCreates()
    {
        $terms = [
            'test1' => ['id' => []],
            'test2' => ['uuid' => []],
            'create:table' => ['name' => []],
        ];

        foreach ($terms as $tableName => $columns) {
            $response = SchemaMethod::get($tableName, $columns)[0];
            $this->assertTrue($response == 'create', $tableName);
        }
    }

    public function testUpdates()
    {
        $terms = [
            'test' => ['name' => []],
            'update:test' => ['id' => []],
        ];

        foreach ($terms as $tableName => $columns) {
            $response = SchemaMethod::get($tableName, $columns)[0];
            $this->assertTrue($response == 'update', $tableName);
        }
    }
}
