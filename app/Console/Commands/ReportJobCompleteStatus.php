<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Events\JobCompletedEvent;
use App\Events\OrderEvent;
use App\Events\ServerCreated;

class ReportJobCompleteStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // php artisan report:job_complete_status --argoId=x123 --status=Success --output='{"data": "foo"}'
    protected $signature = '
        report:job_complete_status 
        { --argoId=: Id of argo JOB to be updated } 
        { --status=: Status of the JOB } 
        { --output=: Some json details } 
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command dispatches an event to the main Application instance with the JOB completion status info.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $var = $this->argument('argument');
        $options = $this->options();
        // event(new ServerCreated($user));
        event(new JobCompletedEvent());
        dd(json_decode($options['output']));
        return 0;
    }
}
