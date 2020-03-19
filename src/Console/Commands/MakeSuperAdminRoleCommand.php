<?php

namespace Lararole\Console\Commands;

use Lararole\Models\Role;
use Lararole\Models\Module;
use Illuminate\Console\Command;

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
        if (Role::whereSlug('super-admin')->first()) {
            $this->error('Super Admin role already exists');

            return;
        }

        $role = Role::create(['name' => 'Super Admin']);
        $this->info('Super admin role created');

        $role->modules()->attach(config('lararole.attach_all_children') ? Module::root()->get() : Module::all(), ['permission' => 'write']);
        $this->info('Root modules are assigned to super admin role with write permission.');
    }
}
