<?php

namespace MylesDuncanKing\SimpleMigration\Test;

use MylesDuncanKing\SimpleMigration\Helpers\MethodArgs;

final class MethodArgsTest extends TestCase
{
    public function testColumns()
    {
        $terms = [
            // From README.md
            'id'                  => ['id', []],
            'softDeletes'         => ['softDeletes', []],
            'string:role,64'      => ['string', ['role', '64']],
            'unique:deleted_at|role,roles_unique_role' => ['unique', [['deleted_at', 'role'], 'roles_unique_role']],
            'role_id'             => ['foreignId', ['role_id']],
            'assigned_at'         => ['timestamp', ['assigned_at']],
            'foreign:role_id'     => ['foreign', ['role_id']],

            // Additional tests
            'secret_identity'     => ['string', ['secret_identity']], // Contains "_id" but not an ID column
            'datetime:ordered_at' => ['datetime', ['ordered_at']], // Override "_at" assumption
            'first_name'          => ['string', ['first_name']], // Test string default assumption
            'bool:login_enabled'  => ['bool', ['login_enabled']],
            'set:type_of_product,veg|dairy' => ['set', ['type_of_product', ['veg', 'dairy']]],
            'decimal:amount,8,2'  => ['decimal', ['amount', '8', '2']],
        ];

        foreach ($terms as $term => $expected) {
            $response = json_encode(MethodArgs::get($term, 'string'));
            $expected = json_encode($expected);
            $this->assertTrue($response == $expected, $term . ' = ' . $response . ' (' . $expected . ')');
        }
    }

    public function testModifiers()
    {
        $terms = [
            // From README.md
            'after:id'            => ['after', ['id']],
            'nullable'            => ['nullable', []],
            'index'               => ['index', []],
            'references:id'       => ['references', ['id']],
            'on:roles'            => ['on', ['roles']],

            // Additional tests
        ];

        foreach ($terms as $term => $expected) {
            $response = json_encode(MethodArgs::get($term));
            $expected = json_encode($expected);
            $this->assertTrue($response == $expected, $term . ' = ' . $response . ' (' . $expected . ')');
        }
    }
}
