<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Teste extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ele:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //$ordinary = CONF_ELEICAO.pl.[índice].e.[índice].cd
        //$typeCand = CONF_ELEICAO.pl.[índice].e.[índice].abr.[índice].cp.[índice].cd

        foreach (\App\Models\Election::ESTADOS as $estado) {
            $state = mb_strtolower($estado);

            $url = "https://resultados.tse.jus.br/oficial/comum/config/ele-c.json";

            $response = Http::get($url)->json();

            dd($response);

            /*


             */

        }

        return 'Sem Dados';
    }
}
