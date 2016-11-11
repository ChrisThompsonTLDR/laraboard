<?php

namespace Christhompsontldr\Laraboard;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class MigrationsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laraboard:migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates migration files needed for Laraboard.';

    /**
     * Suffix of the migration name.
     *
     * @var string
     */
    protected $migrationSuffix = 'laraboard_tables';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        //  move the revision table
        if (!file_exists(database_path('migrations/2013_04_09_062329_create_revisions_table.php')) && $fs = fopen(database_path('migrations/2013_04_09_062329_create_revisions_table.php'), 'x')) {
            fwrite($fs, file_get_contents(base_path('vendor/venturecraft/revisionable/src/migrations/2013_04_09_062329_create_revisions_table.php')));
            fclose($fs);
        }

        $this->laravel->view->addNamespace('laraboard', substr(__DIR__, 0, -8).'migrations');

        $postsTable         = 'posts';
        $subscriptionsTable = 'subscriptions';
        $alertsTable        = 'alerts';

        $this->line('');
        $this->info("Tables: $postsTable, $subscriptionsTable, $alertsTable");

        $message = $this->generateMigrationMessage([
            $postsTable,
            $subscriptionsTable,
            $alertsTable,
        ]);

        $this->comment($message);

        $existingMigrations = $this->alreadyExistingMigrations();

        if ($existingMigrations) {
            $this->line('');

            $this->warn($this->getExistingMigrationsWarning($existingMigrations));
        }

        $this->line('');

        if (! $this->confirm("Proceed with the migration creation?", "yes")) {
            return;
        }

        $this->line('');

        $this->info("Creating migration...");

        if ($this->createMigration()) {
            $this->info("Migration successfully created!");
        } else {
            $this->error(
                "Couldn't create migration.\n".
                "Check the write permissions within the database/migrations directory."
            );
        }

        $this->line('');
    }

    /**
     * Create the migration.
     *
     * @return bool
     */
    protected function createMigration()
    {
        $migrationPath = $this->getMigrationPath();

        $output = $this->laravel->view->make('laraboard::migrations')->render();

        if (!file_exists($migrationPath) && $fs = fopen($migrationPath, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    }

    /**
     * Generate the message to display when running the
     * console command showing what tables are going
     * to be created.
     *
     * @return string
     */
    protected function generateMigrationMessage(array $tables)
    {
        return 'A migration that creates ' . implode(', ', $tables) . ' tables will be created in database/migrations directory';
    }

    /**
     * Build a warning regarding possible duplication
     * due to already existing migrations
     *
     * @param  array $existingMigrations
     * @return string
     */
    protected function getExistingMigrationsWarning(array $existingMigrations)
    {
        if (count($existingMigrations) > 1) {
            $base = "Laraboard migrations already exist.\nFollowing files were found: ";
        } else {
            $base = "Laraboard migration already exists.\nFollowing file was found: ";
        }

        return $base . array_reduce($existingMigrations, function ($carry, $fileName) {
            return $carry . "\n - " . $fileName;
        });
    }

    /**
     * Check if there is another migration
     * with the same suffix.
     *
     * @return array
     */
    protected function alreadyExistingMigrations()
    {
        $matchingFiles = glob($this->getMigrationPath('*'));

        return array_map(function ($path) {
            return basename($path);
        }, $matchingFiles);
    }

    /**
     * Get the migration path.
     *
     * The date parameter is optional for ability
     * to provide a custom value or a wildcard.
     *
     * @param  string|null $date
     * @return string
     */
    protected function getMigrationPath($date = null)
    {
        $date = $date ?: \Carbon\Carbon::now()->format('Y_m_d_His');

        return database_path("migrations/${date}_{$this->migrationSuffix}.php");
    }
}