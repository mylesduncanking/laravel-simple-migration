# Install via composer

`composer require mylesduncanking/laravel-simple-migration`

# Getting started

Firstly you will need to extend the `MylesDuncanKing\SimpleMigration\SimpleMigration` class.

Create a new migration file using the same syntax as a default laravel artisan migration but specify that you would like a `simple-migration`. For example `php artisan make:simple-migration your_migration_name`

Within the migration file you will see a new `protected array` property called `$migration` which is where you will define your migration logic.

The format of the `$migration` array is as follows:
```php
protected array $migration = [
    'TABLE NAME' => [
        'COLUMN NAME' => ['COLUMN MODIFIERS' /** Additional modifiers **/],
        /* Additional columns */
    ],
    /* Additional tables */
];
```

# Table naming convention

Within the `$migration` property create a key for each table you want to migrate. Within each sub array is where you define the column changes.

If an `id` or `uuid` column is defined within the column set then the table will be created, otherwise it will be updated. You can overwrite this by prefixing the table name with either `create:` or `update:` depending on the method you would like to force.

For example:
```php
protected array $migration = [
    /* This table would be created as it contains an 'id' column */
    'table_to_be_created' => [
        'id'
        'name',
        'date:dob' => ['nullable'],
        'timestamps',
    ],

    /* This table would be updated as it doesn't contains an 'id' or 'uuid' column */
    'table_to_be_updated' => [
        'name' => ['after:id']
    ],

    /* A table of name "pivot_table" would be created as the method has been defined */
    'create:pivot_table' => [
        'foreignId:key_1' => ['index'],
        'foreignId:key_2' => ['index'],
    ],
];
```

# How to format column keys

The column is passed as the key within the table array.

The format should be defined as **{ Type }**:**{ Column name }**. For example `integer:quantity`

If you want to pass additional parameters to the type method you can seperate these by a comma. If an array is required prefix the definition with `arr:` and seperate values with a pipe `|`. For example `set:eye_color,arr:blue|green|brown|other`

If you don't pass a type then an assumption will be made as to what type should be used.

|  | Assumed type |
| --- | --- |
| Ends in `_id` | foreignId |
| Ends in `_at` | timestamp |
| Anything else | string |

You can modify these assumptions by running `php artisan vendor:publish --tag=simplemigration` and editing `config/simplemigration.php`

More information on valid laravel column types can be found in [Laravel's documentation](https://laravel.com/docs/9.x/migrations#available-column-types).


# How to format column modifiers

As the value of each column you pass an array. This array can either be empty or define modifiers to the column.

Each value in the array should follow the format of **{ Modifier }**:**{ Parameters }**. For example `after:id`

More information on valid laravel column modifiers can be found in [Laravel's documentation](https://laravel.com/docs/9.x/migrations#column-modifiers).

# Example migration
```
<?php

use MylesDuncanKing\SimpleMigration\SimpleMigration;

class ExampleMigration extends SimpleMigration
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
