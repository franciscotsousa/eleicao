<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Http;

class Position extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'el:position';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load all positions';

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
        $urlBase = Env::get('URL_BASE_CONTAS');

        $states = \App\Models\State::query()
            ->whereNotIn('cd_state', ['BR', 'ZZ'])
            ->get();

        \App\Models\Position::create([
            'name' => 'Presidente',
            'cd_position' => 1,
        ]);

        \App\Models\Position::create([
            'name' => 'Vide-presidente',
            'cd_position' => 2,
        ]);

        foreach ($states as $state) {
            $endPoint = "eleicao/listar/municipios/2040602022/$state->cd_state/cargos";

            $response = Http::withHeaders([
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'Host' => 'divulgacandcontas.tse.jus.br',
                'User-Agent' => Env::get('APP_NAME'),
            ])
                ->get($urlBase . $endPoint)
                ->json('cargos');

            foreach ($response as $value)
            {
                $payload[] =[
                    'nome' => $value['nome'],
                    'codigo' => $value['codigo'],
                ];
            }
        }

        foreach (collect($payload)->unique() as $value) {

            \App\Models\Position::create([
                'name' => $value['nome'],
                'cd_position' => $value['codigo'],
            ]);
        }
        print 'Importação realizada com sucesso!' . PHP_EOL;

        return 0;
    }
}
