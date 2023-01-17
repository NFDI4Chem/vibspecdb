<?php

namespace App\Http\Controllers;

use App\Actions\Assay\CreateAssay;
use App\Actions\Assay\UpdateAssay;

use App\Models\Assay;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Actions\ConfirmPassword;
use Auth;

class AssayController extends Controller
{
    public function store(Request $request, CreateNewAssay $creator)
    {
        $assay = $creator->create($request->all());
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'assay-created');
    }

    public function update(Request $request, UpdateAssay $updater, Assay $assay)
    {
        $updater->update($assay, $request->all());
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'assay-updated');
    }


    public function assays(Request $request, Assay $assay)
    {
        return Inertia::render('Assay/Assays', [
            'assay' => $assay,
            'study' => $assay->study
        ]);
    }


    public function settings(Request $request, Assay $assay)
    {
        return Inertia::render('Assay/Settings', [
            'assay' => $assay,
            'project' => $assay->study
        ]);
    }

    public function destroy(Request $request, StatefulGuard $guard, Assay $assay)
    {
        $confirmed = app(ConfirmPassword::class)(
            $guard,
            $request->user(),
            $request->password
        );

        if (! $confirmed) {
            throw ValidationException::withMessages([
                'password' => __('The password is incorrect.'),
            ]);
        }

        $assay->delete();

        // TODO
        return redirect()->route('dashboard');
    }

    public function activity(Request $request, Assay $assay)
    {
        return response()->json(['audit' => $assay->audits()->with('user')->orderBy('created_at', 'desc')->get()]);
    }

}
