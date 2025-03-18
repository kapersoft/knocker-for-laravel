<?php

namespace kapersoft\KnockerForLaravel\Commands;

use Illuminate\Console\Command;

class KnockerForLaravelCommand extends Command
{
    public $signature = 'knocker-for-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
