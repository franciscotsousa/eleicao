<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')
                ->constrained('states')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('position_id')
                ->constrained('positions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->bigInteger('candidate_id');
            $table->string('display_name');
            $table->integer('number');
            $table->string('name');
            $table->string('affiliated_name');
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
        Schema::dropIfExists('candidates');
    }
}
