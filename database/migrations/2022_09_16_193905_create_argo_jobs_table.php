<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArgoJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('argo_jobs', function (Blueprint $table) {
            $table->id();
            $table->uid('submit_uid')->unique();
            $table->string('argo_uid')->nullable();
            $table->string('type')->nullable('process_data_job');
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('team_id')->nullable();
            $table->foreignId('owner_id')->nullable();
            $table->foreignId('project_id')->nullable();
            $table->foreignId('study_id')->nullable();
            $table->date('finishedAt')->nullable();
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
        Schema::dropIfExists('argo_jobs');
    }
}
