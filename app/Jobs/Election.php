<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class Election implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
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
                        echo $abr["ht"]. '<br>'; //Last Update Time
                    }
                }
            }
        }
    }
}
