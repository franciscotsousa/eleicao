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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $url = Env::get('URL_BASE_CONTAS') . "eleicao/listar/municipios/2040602022/SP/cargos";

        $response = Http::get($url)->json();

        dd($response);
    }
}
