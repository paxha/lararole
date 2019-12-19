<?php

namespace Lararole\Console\Commands;

use Lararole\Models\Module;
use Illuminate\Console\Command;

class AssignSuperAdminRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign-super-admin-role {--user=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigning super admin role to user by user id';

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
        config('lararole.providers.users.model')::find($this->option('user'))->assignSuperAdminRole();

        $this->comment('Super Admin Role assigned to User');
    }
}
