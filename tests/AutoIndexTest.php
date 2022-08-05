<?php

namespace MylesDuncanKing\SimpleMigration\Test;

use MylesDuncanKing\SimpleMigration\Helpers\AutoIndex;

final class AutoIndexTest extends TestCase
{
    public function testAutoIndex()
    {
        $input = [
            ['no_index' => []],
            ['define_index' => ['index']],
            ['auto_index_id' => []],
            ['disable_auto_index_id' => ['noIndex']],
            ['bad_config_id' => ['index', 'noIndex']],
        ];
        $output = [
            ['no_index' => []],
            ['define_index' => ['index']],
            ['auto_index_id' => ['index']],
            ['disable_auto_index_id' => []],
            ['bad_config_id' => ['index']],
        ];

        foreach ($input as $key => $columns) {
            $expected = $output[$key];
            $actual = AutoIndex::apply($columns);
            $this->assertTrue(json_encode($expected) == json_encode($actual), json_encode($actual));
        }
    }
}
