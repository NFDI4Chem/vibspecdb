<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Str;


use App\Models\FileSystemObject;


class JobDeleteForceSheduler implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [2, 10, 20];
    public $maxExceptions = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct() { }

    private function cascadeDelete($files = []) {
      foreach ($files as $child) {
          $this->cascadeDelete($child->children);
          $child->forceDelete();
          // TODO add remove from Minio
      }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

      $files = FileSystemObject::onlyTrashed()
        ->where('deleted_at', '<=', now()->subWeek())->get();   
      
      collect($files)->map(function ($file) {
          $this->cascadeDelete($file->children);
          $file->forceDelete();
      });
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $e)
    {
    }
}
