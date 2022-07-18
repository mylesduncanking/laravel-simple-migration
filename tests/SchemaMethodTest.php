<?php

namespace MylesDuncanKing\SimpleMigration\Test;

use MylesDuncanKing\SimpleMigration\Helpers\SchemaMethod;

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
            $this->assertTrue($response == 'create', $tableName . ': ' . $response);
        }
    }

    public function testUpdates()
    {
        $terms = [
            'test' => ['name' => []],
            'table:test' => ['id' => []],
        ];

        foreach ($terms as $tableName => $columns) {
            $response = SchemaMethod::get($tableName, $columns)[0];
            $this->assertTrue($response == 'table', $tableName . ': ' . $response);
        }
    }
}
