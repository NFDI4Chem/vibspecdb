<?php

namespace App\Actions\ArgoJob;

use App\Models\ArgoJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class SubmitArgoJob
{
    /**
     * Create a project.
     *
     * @param  array  $input
     * @return \App\Models\ArgoJob
     */
    public function submit(string $JOB_ID, array $input)
    {

      try {

        $user_config = [
          'input_data' => array_key_exists('input_data', $input) ? $input['input_data'] : [],
          'out_data' => array_key_exists('out_data', $input) ? $input['out_data'] : ['prefix' => ''],
          'hook_token' => array_key_exists('token', $input) ? $input['token'] : '',
        ];

        $SUBMIT_URL = Str::finish(config('jobs.argo_fastapi_service_url'), '/v1/submit');

        $limits = [
          'containers' => [
            [
              'name' => 'main',
              'resources' => [
                'limits' => [
                  'cpu' => '500m',
                  'memory' => '1000Mi',
                ],
              ]
            ],
          ],
        ];

        $parameters = [
            "parameters" => [
                [
                  "name"  => "base_image",
                  "value" => "python:3.9"
                ],
                [
                  "name" => "limit_resources",
                  "value" => (string) json_encode($limits)
                ]
            ]
        ];

        $job_info = [
            'id' => $JOB_ID,
            'model_id' => 'xstxmd',
            'type' => 'process_data_job',
        ];

        $SUBMIT_DATA = array_merge(
            $user_config,
            $parameters,
            $job_info,
        );
      

        $response = Http::post($SUBMIT_URL, $SUBMIT_DATA);
          
        $result = [
            'status' => true,
            'response' => json_decode($response->body())
        ];

        return $result;

      } catch (Throwable $exception) {
          return response()
              ->json([
                  'message' => $exception->getMessage(),
              ]);
      }

    }
}
