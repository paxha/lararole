<?php

namespace Lararole\Console\Commands;

use Lararole\Models\Module;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MakeViewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new views of module with directory path';

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
        foreach (Module::isLeaf()->get() as $module) {
            $view = 'modules';
            foreach ($module->ancestors->reverse() as $ancestor) {
                $view .= '.'.$ancestor->slug;
            }
            $view .= '.'.$module->slug;

            $this->view($view);
        }
    }

    private function view($view)
    {
        if (! view()->exists($view.'.index')) {
            Artisan::call("make:view '{$view}' --resource --section=title --section=content --section=myscript");
            $this->info('Resource View "'.$view.'" Successfully Created');
        } else {
            $this->comment($view.' Already Exists');
        }
    }
}
