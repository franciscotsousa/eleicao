<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class LoadAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'el:loadAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load all commands';

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
     * @return int
     */
    public function handle()
    {
        print 'Start loading | ' . Carbon::createFromFormat('Y-m-d H:i:s', now()) . PHP_EOL . PHP_EOL;

        shell_exec('php artisan migrate:fresh --seed');

        print 'Start load data of state | ' . Carbon::createFromFormat('Y-m-d H:i:s', now()) . PHP_EOL;
        shell_exec('php artisan el:state');
        print 'Finished load ' . Carbon::createFromFormat('Y-m-d H:i:s', now()) . PHP_EOL . PHP_EOL;

        print 'Start load data of city | ' . Carbon::createFromFormat('Y-m-d H:i:s', now()) . PHP_EOL;
        shell_exec('php artisan el:city');
        print 'Finished load ' . Carbon::createFromFormat('Y-m-d H:i:s', now()) . PHP_EOL . PHP_EOL;

        print 'Start load data of zone | ' . Carbon::createFromFormat('Y-m-d H:i:s', now()) . PHP_EOL;
        shell_exec('php artisan el:zone');
        print 'Finished load ' . Carbon::createFromFormat('Y-m-d H:i:s', now()) . PHP_EOL . PHP_EOL;

        print 'Start load data of position | ' . Carbon::createFromFormat('Y-m-d H:i:s', now()) . PHP_EOL;
        shell_exec('php artisan el:position');
        print 'Finished load ' . Carbon::createFromFormat('Y-m-d H:i:s', now()) . PHP_EOL . PHP_EOL;

        print 'Start load data of candidate | ' . Carbon::createFromFormat('Y-m-d H:i:s', now()) . PHP_EOL;
        shell_exec('php artisan el:candidate');
        print 'Finished load ' . Carbon::createFromFormat('Y-m-d H:i:s', now()) . PHP_EOL . PHP_EOL;

        print 'Load general finished  | ' . Carbon::createFromFormat('Y-m-d H:i:s', now()) . PHP_EOL;

        return 0;
    }
}
