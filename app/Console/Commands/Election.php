<?php

namespace App\Console\Commands;

use App\Models\Config;
use App\Models\State;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Election extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'el:election {id}';

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
        $config = Config::find($this->argument('id'));

        if ($config->ordinary == 545 || $config->ordinary == 547) {
            $states = State::query()
                ->where('second_governor', true)
                ->get();
        }
        else
        {
            $states = State::query()
                ->get();
        }

        foreach ($states as $item) {
            $state = mb_strtolower($item->cd_state);

            $url = "https://resultados.tse.jus.br/oficial/ele2022/$config->ordinary/dados/$state/$state-c0001-e000$config->ordinary-v.json";

            $response = Http::get($url)->json('abr');

            dd($response);

            echo $response[0]["cdabr"] . " - "; //State
            echo $response[0]["pst"] . " - "; //Percentege
            echo $response[0]["dt"] . " - "; //Last Update Date
            echo $response[0]["ht"] . PHP_EOL; //Last Update Hour

        }
    }
}
