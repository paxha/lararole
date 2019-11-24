<?php

namespace Lararole\Console\Commands;

use Illuminate\Console\Command;
use Lararole\Models\Module;
use Lararole\Models\Role;

class MakeSuperAdminRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:super-admin-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Super Admin Role';

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
        if (Role::whereSlug('super_admin')->first()) {
            $this->error('Super Admin role already exists');

            return;
        }

        $role = Role::create(['name' => 'Super Admin']);
        $this->info('Super Admin role created');

        $role->modules()->attach(Module::where('module_id', '=', null)->pluck('id'), ['permission' => 'write']);
    }
}
