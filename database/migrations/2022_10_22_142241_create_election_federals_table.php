<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectionFederalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('election_federals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')
                ->constrained('states')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('config_id')
                ->constrained('configs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('candidate_id')
                ->constrained('candidates')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unique(['state_id', 'config_id', 'candidate_id']);
            $table->float('percentage');
            $table->date('last_update_date');
            $table->time('last_update_hour');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('election_federals');
    }
}
