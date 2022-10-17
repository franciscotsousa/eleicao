<?php

namespace App\Console\Commands;

use App\Models\State as States;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class State extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'el:state';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load all states';

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
        $secondGovernor = array('AL','AM','SC','SE','BA','ES','MS','PB','PE','RS','RR','SP');
        $secondSenator = array('AL','AM','SC','SE','RO');

        States::create([
            'name' => 'Brasil',
            'cd_state' => 'BR',
            'second_governor' => false,
            'second_senator' => false,
        ]);

        $url = 'https://resultados.tse.jus.br/oficial/ele2022/545/config/mun-e000545-cm.json';

        $response = Http::get($url)->json();

        if (Str::lower(gettype($response)) == 'array')
        {
            foreach ($response['abr'] as $state)
            {
                States::create([
                    'name' => $state['ds'],
                    'cd_state' => $state['cd'],
                    'second_governor' => in_array($state['cd'], $secondGovernor),
                    'second_senator' => in_array($state['cd'], $secondSenator),
                    ]);
            }
        }

        print 'Importação realizada com sucesso!' . PHP_EOL;
        return 0;
    }


}
