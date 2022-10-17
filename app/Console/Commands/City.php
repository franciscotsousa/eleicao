<?php

namespace App\Console\Commands;

use App\Models\State;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class City extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'el:city';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load all cities';

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
    public function handle() : int
    {
        $url = 'https://resultados.tse.jus.br/oficial/ele2022/545/config/mun-e000545-cm.json';

        $response = Http::get($url)->json();

        foreach ($response['abr'] as $items)
        {
            $state = State::query()
                ->where('cd_state', $items)
                ->get();

            foreach ($items['mu'] as $item)
            {
                $state[0]->cities()->create([
                    'name' => $item['nm'],
                    'cd_city' => $item['cd'],
                    'cdi_city' => $item['cdi'],
                ]);
            }
        }

        print 'Importação realizada com sucesso!' . PHP_EOL;

        return 0;
    }
}
