<?php
  
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
  
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('argo_jobs_files', function (Blueprint $table) {
            $table->foreignId('job_id')->constrained('argo_jobs');
            $table->foreignId('file_id')->constrained('file_system_objects');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::dropIfExists('argo_jobs_files');
    }
};