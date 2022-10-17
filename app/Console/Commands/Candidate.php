<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Http;

class Candidate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'el:candidate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load all candidates';

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
        $urlBase = Env::get('URL_BASE_CONTAS');

        $states = \App\Models\State::query()
            ->whereNotIn('cd_state', ['ZZ'])
            ->get();

        $positions = \App\Models\Position::query()
            ->whereNotIn('id', ['8'])
            ->get();

        foreach ($states as $state)
        {
            foreach ($positions as $position)
            {
                $endPoint = "candidatura/listar/2022/$state->cd_state/2040602022/$position->cd_position/candidatos";

                $response = Http::withHeaders([
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Connection' => 'keep-alive',
                    'Host' => 'divulgacandcontas.tse.jus.br',
                    'User-Agent' => Env::get('APP_NAME'),
                ])
                    ->get($urlBase . $endPoint)
                    ->json('candidatos');

                foreach ($response as $value)
                {
                    $state->candidates()->create([
                        'candidate_id' => $value['id'],
                        'display_name' => $value['nomeUrna'],
                        'name' => $value['nomeCompleto'],
                        'number' => $value['numero'],
                        'affiliated_name' => $value['partido']['sigla'] == null ? 'NÃO DIVULGADO' : $value['partido']['sigla'],
                        'position_id' => \App\Models\Position::findOrFail($value['cargo']['codigo'])->id,
                    ]);

                }
            }
        }

        print 'Importação realizada com sucesso!' . PHP_EOL;

        return 0;
    }
}
