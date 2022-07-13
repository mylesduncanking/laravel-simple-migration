`composer require mylesduncanking/laravel-simple-migration`

```
<?php

use MylesDuncanKing\SimpleMigration\SimpleMigration;

class AddColumnsToOpportunities extends SimpleMigration
{
    protected array $migration = [
        'roles' => [
            'id' => [],                                           // $table->id(); // = id so $table->id();
            'softDeletes' => [],                                  // $table->softDeletes();
            'string:role,64' => [],                               // $table->string('role', 64);
            'unique:arr:deleted_at|role,roles_unique_role' => [], // $table->unique(['deleted_at', 'role'], 'roles_unique_role');
        ],
        'users' => [
            'role_id' => ['after:id', 'nullable', 'index'],       // ends in _id so $table:foreignId('role_id');
            'assigned_at' => ['after:role_id', 'nullable'],       // ends in _at so $table->timestamp('assigned_at');
            'foreign:role_id' => ['references:id', 'on:roles'],   // $table->foreign('role_id')->references('id')->on('roles');
        ]
    ];
}
```
