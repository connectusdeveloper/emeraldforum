<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Laravel\Jetstream\Jetstream;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\{Hash, Validator};

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input)
    {
        $settings = site_config();
        if (($settings['registration'] ?? null) != 1) {
            return to_route('threads')->with('error', __('Registration is disabled.'));
        }

        Validator::make($input, [
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:25', 'unique:users'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms'    => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'username' => $input['username'],
            'password' => Hash::make($input['password']),
            'active'   => ($settings['mode'] ?? null) == 'Public',
        ]);
        $user->assignRole('member');
        return $user;
    }
}
