<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Zone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'el:zone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load all zone for city';

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
        $url = 'https://resultados.tse.jus.br/oficial/ele2022/545/config/mun-e000545-cm.json';

        $response = Http::get($url)->json();

        foreach ($response['abr'] as $cities)
        {
            foreach ($cities['mu'] as $items)
            {
                $city = \App\Models\City::query()
                    ->where('cd_city', $items)
                    ->get();

                foreach ($items['z'] as $item)
                {
                    $city[0]->zones()->create([
                        'cd_zone' => $item
                    ]);
                }
            }
        }
        print 'Importação realizada com sucesso!' . PHP_EOL;

        return 0;
    }
}
