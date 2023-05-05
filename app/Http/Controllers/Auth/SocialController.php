<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LinkedSocialAccount;
use App\Models\Team;
use App\Models\User;
use Auth;
use Illuminate\Auth\Events\Registered;

use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Facades\Socialite;


class SocialController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($service)
    {
        if ($service === 'gitlab') {
            return Socialite::driver($service)
                ->setHost(env('GITLAB_BASE_URL'))
                ->stateless()
                ->redirect();
        } else {
            return Socialite::driver($service)->redirect();
        }
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($service)
    {
        $providerUser = null;

        if ($service === 'gitlab') {
            $providerUser = Socialite::driver($service)
                ->setHost(env('GITLAB_BASE_URL'))
                ->stateless()
                ->user();
        } else {
            try {
                $providerUser = Socialite::driver($service)->user();
            } catch (InvalidStateException $e) {
                $providerUser = Socialite::driver($service)->stateless()->user();
            }
        }

        $providerUserID = $providerUser->getId() ?
            $providerUser->getId() :
            ($providerUser->sub ? $providerUser->sub : '');

        $linkedSocialAccount = LinkedSocialAccount::where('provider_name', $service)
            ->where('provider_id', $providerUserID)
            ->first();

        $user = null;

        if ($linkedSocialAccount) {
            $user = $linkedSocialAccount->user;
        } else {
            if ($email = $providerUser->getEmail()) {
                $user = User::where('email', $email)->first();
            }
            if (!$user) {
                list ($first_name, $last_name) = explode(' ', $providerUser->getName());
                $user = tap(
                    User::create([
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $providerUser->getEmail()
                    ]
                ), function (User $user) {
                    $user->ownedTeams()->save(Team::forceCreate([
                        'user_id' => $user->id,
                        'name' => $user->first_name."'s Team",
                        'personal_team' => true,
                    ]));
                });
            }
            $user->linkedSocialAccounts()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $service,
            ]);
            event(new Registered($user));
        }
        Auth::login($user);
        return redirect('/dashboard');
    }
}
