# Laravel Simple Migration

## 📦 Installation

```bash
composer require mylesduncanking/laravel-simple-migration
```

---

## ❓ Why Use This Package?

Laravel's migrations are powerful but can become verbose and repetitive, especially for simple table structures or frequent seed/migrate workflows.

**Laravel Simple Migration** helps you:

- Write cleaner, array-based migration definitions
- Avoid boilerplate like `->after()` and `->index()`
- Automatically run seeders alongside migrations
- Maintain clarity and control with a minimal syntax layer

Perfect for rapid prototyping, internal tools, or any dev who loves less noise and more flow.

---

## 🚀 Getting Started

This package builds on Laravel's native migration system. If you're familiar with [Laravel migrations](https://laravel.com/docs/9.x/migrations), this will feel familiar.

To create a simple migration:

```bash
php artisan make:simple-migration your_migration_name
```

Your migration file will contain a `$migration` array for defining schema changes.

### Format

```php
protected array $migration = [
    'TABLE_NAME' => [
        'COLUMN_NAME' => ['MODIFIERS'],
        // Additional columns...
    ],
    // Additional tables...
];
```

---

## 🔁 Auto-After Functionality

To save time when adding multiple columns, this package adds them sequentially by default. This removes the need for `->after('column')`.

To disable this:

```bash
php artisan vendor:publish --tag=simplemigration
```

Then set `config/simplemigration.php > auto_after` to `false`.

---

## ⚡ Auto-Index Functionality

By default, any column ending in `_id` will automatically get an `->index()` modifier.

Customize this behavior:

```bash
php artisan vendor:publish --tag=simplemigration
```

Then edit the regex rules in `config/simplemigration.php > auto_index`.

To exclude a specific column from auto-indexing, add `noIndex` in its modifiers array.

---

## 🌱 Seeder Functionality

Table creations or updates often require seeders. This package uses Laravel's migration tracking to automatically run seeders during the `up()` method.

```php
protected array $seeders = [
    'roles',
];
```

> **Note:** Seeders only run during `up()` by default.
> To run seeders during rollback (`down()`), use `beforeDown()` or `afterDown()` and call `runSeeder()` manually.

---

## 📘 Table Naming Convention

If a table includes an `id` or `uuid` column, it is assumed to be a new table. Otherwise, the table is assumed to be updated.

To explicitly define the action, use:

- `create:table_name`
- `update:table_name`

Or adjust assumptions globally:

```php
config/simplemigration.php > type_assumptions
```

### Example

```php
protected array $migration = [
    'table_to_be_created' => [
        'id',
        'name',
        'date:dob' => ['nullable'],
        'timestamps',
    ],

    'table_to_be_updated' => [
        'name' => ['after:id']
    ],

    'create:pivot_table' => [
        'foreignId:key_1' => ['index'],
        'foreignId:key_2' => ['index'],
    ],
];
```

---

## 🏷️ Column Key Format

Format keys as `{type}:{column}`.

### Examples:

- `integer:quantity`
- `set:status`
- `foreignId:user_id`
- `date:dob` => ['nullable']

---

## ✅ Supported Modifiers

You can use all default Laravel column modifiers in array format:

- `nullable`
- `default:value`
- `unique`
- `index`
- `after:other_column`
- `comment:some text`
- `noIndex` (custom)

---

## ✨ Helper Methods

### beforeDown()
Hook to perform actions before the `down()` method runs.

### afterDown()
Hook to perform actions after the `down()` method runs.

### runSeeder($seeder)
Run a seeder manually during `up()` or `down()`.

---

## ✏️ Example Seeder Triggered in Migration

```php
protected array $migration = [
    'roles' => [
        'id',
        'name',
    ],
];

protected array $seeders = [
    'Role'
];
```

This will run `Role` when the migration is applied.

---

## 📁 Config File

To customize assumptions and toggles:

```bash
php artisan vendor:publish --tag=simplemigration
```

Edit `config/simplemigration.php` to:

- Enable/disable auto-index or auto-after
- Add custom regex rules
- Set type assumptions for new vs update

