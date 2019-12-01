<?php

namespace Lararole\Console\Commands;

use Lararole\Models\Module;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateModulesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:modules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert all modules and sub modules';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (config('lararole.modules') as $module) {
            $m = Module::create([
                'name' => $module['name'],
                'icon' => @$module['icon'],
            ]);

            if (@$module['modules']) {
                $m->create_modules(@$module['modules']);
            }
        }

        $this->info('All modules and sub modules migrated successful!');
    }
}
