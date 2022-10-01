<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

// use App\Models\Job;


class JobsController extends Controller
{
    public function check(Request $request, string $type)
    {
        return [
          'status' => true
        ]
    }

}
