<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Election extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'el:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load election data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        foreach (\App\Models\Election::ESTADOS as $estado)
        {
            $state = mb_strtolower($estado);

            $url = "https://resultados.tse.jus.br/oficial/ele2022/544/dados/$state/$state-c0001-e000544-v.json";

            $response = Http::get($url)->json();

            foreach ($response as $resp)
            {
                if(gettype($resp) == 'array')
                {
                    foreach ($resp as $abr)
                    {
                        echo $abr["cdabr"] . ";"; //State
                        echo $abr["pst"] . ';'; //Percentege
                        echo $abr["dt"] . ';'; //Last Update Date
                        echo $abr["ht"] . PHP_EOL; //Last Update Time
                    }
                }
            }
        }
    }
}
