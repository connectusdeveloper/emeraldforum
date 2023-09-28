<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\{SocialAccount, User};
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function login($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            $account = SocialAccount::where([
                'provider'    => $provider,
                'provider_id' => $socialUser->getId()
            ])->first();

            if ($account) {
                auth()->login($account->user);
                return to_route('threads')->with('message', __('You are successfully logged in.'));
            }

            $user = User::where(['email' => $socialUser->getEmail()])->first();

            if (!$user) {
                $user = User::create([
                    'name'     => $socialUser->getName(),
                    'email'    => $socialUser->getEmail(),
                    'password' => Hash::make(str()->random()),
                    'username' => random_username($socialUser->getName()),
                ]);
            }

            $user->socialAccounts()->create([
                'provider'    => $provider,
                'provider_id' => $socialUser->getId(),
            ]);

            auth()->login($user);
            return to_route('threads')->with('message', __('You are successfully logged in.'));
        } catch(\Exception $e) {
            logger()->error('Social auth error: ', ['error' => $e]);
            return to_route('login')->with('error', $e->getMessage());
        }
    }

    public function redirect($provider)
    {
        $settings = site_config();
        if (($settings[$provider . '_login'] ?? null) && ($settings[$provider . '_client_id'] ?? null) && ($settings[$provider . '_client_secret'] ?? null)) {
            config([
                'services.' . $provider . '.client_id'     => $settings[$provider . '_client_id'] ?? null,
                'services.' . $provider . '.client_secret' => $settings[$provider . '_client_secret'] ?? null,
                'services.' . $provider . '.redirect'      => route('social.callback', ['provider' => $provider]),
            ]);
        } else {
            return to_route('login')->with('error', __('Request failed, please contact site admin.'));
        }

        return Socialite::driver($provider)->redirect();
    }
}
