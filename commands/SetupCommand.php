<?php

namespace Christhompsontldr\Laraboard;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laraboard:setup {--no-laratrust}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup migration and models for Laraboard';

    /**
     * Commands to call with their description
     *
     * @var array
     */
    protected $calls = [
        'laraboard:migrations' => 'Creating migrations',
        'laraboard:add-trait'  => 'Adding LaraboardUser trait to User model',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        foreach ($this->calls as $command => $info) {
            $this->line(PHP_EOL . $info);
            $this->call($command);
        }

        //  allow users to not setup laratrust
        if (!$this->option('no-laratrust')) {
            $this->call('laratrust:setup');
        }
    }
}