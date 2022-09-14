<?php

namespace App\Services;

class ArgoService extends Service
{
    const ARGO_URL = '';

    public function check()
    {
        return [
          'connection' => TRUE
        ]
    }
}