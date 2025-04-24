# Install via composer

`composer require mylesduncanking/laravel-simple-migration`

# Getting started

To use this an understanding of how Laravel's migrations work is required.
"Migrations are like version control for your database, allowing your team to define and share the application's database schema definition. If you have ever had to tell a teammate to manually add a column to their local database schema after pulling in your changes from source control, you've faced the problem that database migrations solve." - [Laravel documentation](https://laravel.com/docs/9.x/migrations)

To use simple migrations, create a new migration file using the same syntax as a default Laravel artisan migration but specify that you would like a `simple-migration`. For example `php artisan make:simple-migration your_migration_name`

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

# Auto-after functionality

To save some time when adding multiple columns to a table, the default behaviour is changed to add columns sequentially. This removes the requirement of adding `->after('foobar')` to every column modifier.

You can disable this behaviour by running `php artisan vendor:publish --tag=simplemigration` and changing `config/simplemigration.php > auto_after` to `false`


# Auto-index functionality

To save some time you can use the automatic index feature. By default this will automatically add the `->index()` modifier to any column ending in `_id`

You can modify these rules by running `php artisan vendor:publish --tag=simplemigration` and changing the values within the `config/simplemigration.php > auto_index` array. **Note: These values are in a regex format.**

You can also specify an auto-index column to not be indexed ad-hoc by passing the `noIndex` option in the modifiers array.

# Seeder functionality

Often table creations or changes go hand-in-hand with seeder but these can be difficult to run automatically. Using the way that Laravel tracks migrations, you can then instruct a seeder file to run too.

Note: The seeder will only run within the `up` method. If you would like to run a seeder during the `down` method too, then you can utilise the `beforeDown` or `afterDown` methods and call the `runSeeder` method.

# Table naming convention

Within the `$migration` property create a key for each table you want to migrate. Within each sub array is where you define the column changes.

If an `id` or `uuid` column is defined within the column set then the table will be created, otherwise it will be updated. You can overwrite this by prefixing the table name with either `create:` or `update:` depending on the method you would like to force.

You can modify these assumptions by running `php artisan vendor:publish --tag=simplemigration` and editing `config/simplemigration.php > type_assumptions`

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

If you want to pass additional parameters to the type method you can seperate these by a comma. If an array is required seperate values with a pipe `|`. For example `set:eye_color,blue|green|brown|other`

If you don't pass a type then an assumption will be made as to what type should be used.

|  | Assumed type |
| --- | --- |
| Ends in `_id` | foreignId |
| Ends in `_at` | timestamp |
| Anything else | string |

You can modify these assumptions by running `php artisan vendor:publish --tag=simplemigration` and editing `config/simplemigration.php > create_triggers`. **Note: This is in a regex format.**

More information on valid Laravel column types can be found in [Laravel's documentation](https://laravel.com/docs/9.x/migrations#available-column-types).


# How to format column modifiers

As the value of each column you pass an array. This array can either be empty or define modifiers to the column.

Each value in the array should follow the format of **{ Modifier }**:**{ Parameters }**. For example `after:id`

More information on valid Laravel column modifiers can be found in [Laravel's documentation](https://laravel.com/docs/9.x/migrations#column-modifiers).


# Example migration

The following migration creates a roles table and add a foreign key into the users table.

```
<?php

use MylesDuncanKing\SimpleMigration\SimpleMigration;

class ExampleMigration extends SimpleMigration
{
    protected array $seeders = [
        'roles'
    ];

    protected array $migration = [
        // Create "roles" table as "id" column is specified
        'roles' => [
            'id',                                       // $table->id();
            'softDeletes',                              // $table->softDeletes();
            'string:role,64',                           // $table->string('role', 64);
            'unique:deleted_at|role,roles_unique_role', // $table->unique(['deleted_at', 'role'], 'roles_unique_role');
        ],

        // Update "users" table as no "id" or "uuid" column is specified
        'users' => [
            'role_id' => ['after:id', 'nullable'],       // ends in _id so $table:foreignId('role_id')->after('id')->nullable()->index();
            'foreign:role_id' => ['references:id', 'on:roles'],   // $table->foreign('role_id')->references('id')->on('roles')->index();
        ]
    ];
}
```
