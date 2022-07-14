<?php

namespace MylesDuncanKing\SimpleMigration\Commands;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

class CreateSimpleMigration extends MigrateMakeCommand
{
    protected $signature = 'make:simple-migration {name : The name of the migration}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}';

    protected $description = 'Create a new simple migration file';

    public function __construct()
    {
        $creator = new MigrationCreator(new FileSystem(), __DIR__ . '/stubs/');
        parent::__construct($creator, app('composer'));
    }
}
