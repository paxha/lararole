<?php

namespace Lararole\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lararole:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Lararole resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Lararole Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'lararole-assets']);

        $this->comment('Publishing Lararole Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'lararole-config']);

        $this->comment('Publishing Lararole Views...');
        $this->callSilent('vendor:publish', ['--tag' => 'lararole-views']);

        $this->info('Lararole scaffolding installed successfully.');
    }
}
