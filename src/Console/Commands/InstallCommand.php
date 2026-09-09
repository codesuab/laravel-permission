<?php

namespace Codesuab\Permission\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'permission:install {--force}';
    protected $description = 'Install Laravel Permission';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--tag' => 'permission-config',
            '--force' => $this->option('force'),
        ]);

        $this->call('migrate');

        $this->info('Laravel Permission installed.');

        return self::SUCCESS;
    }
}
