<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use App\Models\Job;


class JobsController extends Controller
{
    public function check(Request $request)
    {
      $result = [
        'status' => true,
      ];
      return $result;
    }

}
