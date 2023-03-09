<?php

namespace App\Http\Controllers\API;

use App\Services\RamanService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SpectraController extends Controller
{

    private RamanService $ramanService;

    public function __construct(RamanService $ramanService)
    {
        // $this->ramanService = $ramanService;
    }

    public function getSpectra(Request $request, RamanService $ramanService)
    {
        try {
            $params = request()->all();
            $data = $ramanService->getSpectra($params);
            return $data;
        } catch(Throwable $e) {
            return redirect()->back()->withErrors([
                'create' => 'Failed to get spectra Data.'
            ]);
        }
    }

}
