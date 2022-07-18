<?php

namespace MylesDuncanKing\SimpleMigration\Test;

use MylesDuncanKing\SimpleMigration\Helpers\AutoAfter;

final class AutoAfterTest extends TestCase
{
    public function testAutoAfter()
    {
        $input = [
            'column_1' => ['index', 'after:initial'],
            'column_2' => [],
            'text:column_3' => ['index'],
            'column_4' => [],
            'column_5' => ['index', 'after:foobar'],
            'text:column_6' => ['index'],
        ];
        $expected = [
            'column_1' => ['index', 'after:initial'],
            'column_2' => ['after:column_1'],
            'text:column_3' => ['index', 'after:column_2'],
            'column_4' => ['after:column_3'],
            'column_5' => ['index', 'after:foobar'],
            'text:column_6' => ['index', 'after:column_5'],
        ];

        $actual = AutoAfter::apply($input);

        $this->assertTrue(json_encode($expected) == json_encode($actual), json_encode($actual));
    }
}
