<?php

declare(strict_types=1);

require __DIR__ . '/../src/MethodArgs.php';

use MylesDuncanKing\SimpleMigration\MethodArgs;
use PHPUnit\Framework\TestCase;

final class MethodArgsTest extends TestCase
{
    public function testColumns()
    {
        $terms = [
            // From README.md
            'id'                  => ['id', []],
            'softDeletes'         => ['softDeletes', []],
            'string:role,64'      => ['string', ['role', '64']],
            'unique:arr:deleted_at|role,roles_unique_role' => ['unique', [['deleted_at', 'role'], 'roles_unique_role']],
            'role_id'             => ['foreignId', ['role_id']],
            'assigned_at'         => ['timestamp', ['assigned_at']],
            'foreign:role_id'     => ['foreign', ['role_id']],

            // Additional tests
            'secret_identity'     => ['string', ['secret_identity']],
            'ordered_at'          => ['timestamp', ['ordered_at']],
            'datetime:ordered_at' => ['datetime', ['ordered_at']],
            'user_id'             => ['foreignId', ['user_id']],
            'first_name'          => ['string', ['first_name']],
            'bool:login_enabled'  => ['bool', ['login_enabled']],
            'set:type_of_product,arr:veg|dairy' => ['set', ['type_of_product', ['veg', 'dairy']]],
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