<?php

namespace Christhompsontldr\Laraboard;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Christhompsontldr\Laraboard\Models\Traits\LaraboardUser;
use Traitor\Traitor;

class AddTraitCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laraboard:add-trait';

    /**
     * Trait added to User model
     *
     * @var string
     */
    protected $targetTrait = LaraboardUser::class;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $userModel = $this->getUserModel();

        if (! class_exists($userModel)) {
            $this->error("Class $userModel does not exist.");
            return;
        }

        if ($this->exists()) {
            $this->error("Class $userModel already uses LaraboardUser trait.");
            return;
        }

        Traitor::addTrait($this->targetTrait)->toClass($userModel);

        $this->info("LaraboardUser trait added successfully");
    }

    /**
     * @return bool
     */
    protected function exists()
    {
        return in_array(LaraboardUser::class, class_uses($this->getUserModel()));
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Add LaraboardUser trait to {$this->getUserModel()} class";
    }

    /**
     * @return string
     */
    protected function getUserModel()
    {
        return Config::get('auth.providers.users.model', 'App\User');
    }
}