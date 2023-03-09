<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class RamanService
{
    private $apiUrl = '';
    private $headers = [];

    public function __construct()
    {
        $this->apiUrl = config('services.fastapi-ramanmetrix.url');
        $this->headers = [
          // 'Authorization' => 'Bearer ' . config('services.fastapi-ramanmetrix.token'),
        ];
    }

    public function getSpectra($input = []) {

      if (!($input)) {
          return [
          'status' => false,
          'error' => 'Error. Wrong type of input data or Empty. { files: Array, rootpath: String }.'
        ];
      }

      $spectraUrl = $this->apiUrl . '/v1/parse_spectra';

      $response = Http::withHeaders($this->headers)
        ->post($spectraUrl, $input);

      return $response->json();
    }
}