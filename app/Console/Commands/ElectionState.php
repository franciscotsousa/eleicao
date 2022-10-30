<?php

namespace App\Console\Commands;

use App\Models\Config;
use App\Models\ElectionState as StateElection;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ElectionState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'el:elecState {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load the entire dataset for the state election';

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

        $states = State::query()
            ->where('cd_state', '!=', 'BR')
            ->get();

        $positions = \App\Models\Position::query()
            ->where('id', '!=', [1,2,4])
            ->get();

        foreach ($positions as $position) {
            foreach ($states as $item) {
                $state = mb_strtolower($item->cd_state);

                $url = "https://resultados.tse.jus.br/oficial/ele2022/$config->ordinary/dados-simplificados/$state/$state-c000$position->cd_position-e000$config->ordinary-r.json";

                $response = Http::get($url)->json();

                print $state . PHP_EOL .$url . PHP_EOL;
                if (!blank($response)) {
                    foreach ($response['cand'] as $value) {

                        $candidate = \App\Models\Candidate::query()
                            ->where('candidate_id', $value['sqcand'])
                            ->first('id');

                        $electionState = $item->electionStates()
                            ->where('config_id', $config->id)
                            ->where('candidate_id', $candidate->id)
                            ->get();

                        $stateElection = new StateElection();

                        if ($electionState->count() > 0) {
                            $stateElection = $electionState[0];
                        }

                        $percentage = str_replace(',', '.', $value['pvap']);

                        $this->saveStateElection($config, $stateElection, $item, $candidate, $response, $percentage);
                    }
                }
            }

        }


        return PHP_EOL;
    }

    private function saveStateElection(Config $config, $stateElection, State $item, $candidate, $response, float $pvap)
    {
        $stateElection->config_id = $config->id;
        $stateElection->state_id = $item->id;
        $stateElection->candidate_id = $candidate->id;
        $stateElection->last_update_date = $this->validateLastUpdateDate($response["dt"]);
        $stateElection->last_update_hour = $response['hg'];
        $stateElection->percentage = $pvap;

        $payload = collect([
            'config_id' => $stateElection->config_id,
            'state_id' => $stateElection->state_id,
            'candidate_id' => $stateElection->candidate_id,
            'last_update_date' => $stateElection->last_update_date,
            'last_update_hour' => $stateElection->last_update_hour,
            'percentage' => $stateElection->percentage,
        ]);

        print $payload . PHP_EOL;

        Log::info($payload);

        $stateElection->save();
    }

    private function validateLastUpdateDate($dt): string
    {
        if (!blank($dt)) {
            return Carbon::createFromFormat('d/m/Y', $dt)->toDateString();
        }
        return now()->format('Y-m-d');
    }

}
