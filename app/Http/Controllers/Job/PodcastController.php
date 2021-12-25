<?php

namespace App\Http\Controllers\Job;

use App\Jobs\ProcessPodcast;
use App\Http\Controllers\Controller;

use App\Models\Podcast;

use Illuminate\Http\Request;

class PodcastController extends Controller
{
    public function store(Request $request)
    {
        $podcastdata = [
            'type' => 'jobexample',
            'message' => 'Hi here, I am your job.'
        ];

        $podcast = Podcast::create($podcastdata);

        // $podcast = new Podcast($podcastdata);
        // return $podcast;

        ProcessPodcast::dispatch($podcast)->delay(now()->addMinutes(1));

        return ['status' => true];
    }

    public function logging(Request $request)
    {
        \Log::info('This is some useful information.');

        \Log::warning('Something could be going wrong.');

        \Log::error('Something is really going wrong.');

        return [
            'logged' => true
        ];
    }
}
