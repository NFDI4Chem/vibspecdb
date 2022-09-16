<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_models', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('team_id')->nullable();
            $table->foreignId('owner_id')->nullable();
            $table->foreignId('project_id')->nullable();
            $table->foreignId('study_id')->nullable();
            $table->json('parameters')->nullable('[]');
            $table->json('out_data')->nullable('{}');
            $table->json('input_data')->nullable('[]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_models');
    }
}
