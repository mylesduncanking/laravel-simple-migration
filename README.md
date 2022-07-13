`composer require mylesduncanking/laravel-simple-migration`

```
<?php

use MylesDuncanKing\SimpleMigration\SimpleMigration;

/**
 * Add a roles table and a foreign key in the users table.
 */
class AddUserRoles extends SimpleMigration
{
    protected array $migration = [
        // 'roles' contains 'id' so a new table
        'roles' => [
            'id' => [],                                           // = id so $table->id('key');
            'softDeletes' => [],                                  // $table->softDeletes();
            'string:role,64' => [],                               // $table->string('role', 64);
            'unique:arr:deleted_at|role,roles_unique_role' => [], // $table->unique(['deleted_at', 'role'], 'roles_unique_role');
        ],
        // 'users' has no 'id' so modifying an existing table
        'users' => [                                              
            'role_id' => ['after:id', 'nullable', 'index'],       // ends in _id so $table:foreignId('role_id')->after('id')->nullable()->index();
            'assigned_at' => ['after:role_id', 'nullable'],       // ends in _at so $table->timestamp('assigned_at')->after('role_id')->nullable();
            'foreign:role_id' => ['references:id', 'on:roles'],   // $table->foreign('role_id')->references('id')->on('roles');
        ]
    ];
}
```
