<?php

namespace App\Console\Commands;

use App\Models\Config;
use App\Models\ElectionFederal as ElectionPresident;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ElectionFederal extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'el:elecFederal {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load all dataset for President';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): string
    {
        $config = Config::find($this->argument('id'));

        $states = State::all();

        foreach ($states as $item) {
            $state = mb_strtolower($item->cd_state);

            $url = "https://resultados.tse.jus.br/oficial/ele2022/$config->ordinary/dados/$state/$state-c0001-e000$config->ordinary-v.json";

            $response = Http::get($url)->json('abr');

            foreach ($response[0]['cand'] as $value)
            {
                $candidate = \App\Models\Candidate::query()
                    ->where('number', '=', $value['n'])
                    ->whereIn('position_id', [1])
                    ->pluck('id');

                $electionFederal = $item->electionFederals()
                    ->where('config_id', $config->id)
                    ->where('candidate_id', $candidate[0])
                    ->get();

                $election = new ElectionPresident();

                if ($electionFederal->count() > 0) {
                    $election = $electionFederal[0];
                }

                $percentage = str_replace(',', '.', $value['pvap']);

                $this->saveElectionFederal($item, $election, $config, $response[0], $candidate[0], $percentage);
            }
        }
        return print 'Finished';
    }

    public function saveElectionFederal($item, $election, $config, $response, $candidate, float $percentage): void
    {

        $election->state_id = $item->id;
        $election->config_id = $config->id;
        $election->candidate_id = $candidate;
        $election->percentage = $percentage;
        $election->last_update_date = $this->validadeLastUpdateDate($response["dt"]);
        $election->last_update_hour = $response["ht"];

        $payload = collect([
            'config_id' => $election->config_id,
            'state_id' => $election->state_id,
            'candidate_id' => $election->candidate_id,
            'last_update_date' => $election->last_update_date,
            'last_update_hour' => $election->last_update_hour,
            'percentage' => $election->percentage,
        ]);


        print $payload . PHP_EOL;

        Log::info($payload);

        $election->save();
    }

    public function validadeLastUpdateDate($dt): string
    {
        if (!blank($dt)) {
            return Carbon::createFromFormat('d/m/Y', $dt)->toDateString();
        }

        return now()->format('Y-m-d');
    }
}
