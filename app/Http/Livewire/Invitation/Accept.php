<?php

namespace App\Http\Livewire\Invitation;

use App\Models\User;
use Livewire\Component;
use App\Models\Invitation;
use WireUi\Traits\Actions;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\PasswordValidationRules;

class Accept extends Component
{
    use Actions;
    use PasswordValidationRules;

    public $form = [];

    public Invitation $invitation;

    public function join()
    {
        $this->validate();
        $user = User::create([
            'active'   => 1,
            'name'     => $this->form['name'],
            'email'    => $this->form['email'],
            'username' => $this->form['username'],
            'password' => Hash::make($this->form['password']),
        ]);
        $user->assignRole('member');
        $sameEmail = $this->form['email'] == $this->invitation->email;
        if ($sameEmail) {
            $user->email_verified_at = now();
            $user->saveQuietly();
        } else {
            $user->sendEmailVerificationNotification();
        }
        $this->invitation->update(['accepted_at' => now(), 'accepted_by' => $user->id]);
        auth()->login($user);

        return to_route('threads')->with('message', $sameEmail ? __('Your account has been created.') : __('Please check your inbox to verify your email address.'));
    }

    public function mount(Invitation $invitation, $code)
    {
        if (auth()->user()) {
            return to_route('threads')->with('info', __('You are logged in.'));
        } elseif ($invitation->accepted_at) {
            return to_route('threads')->with('error', __('Invitation has already accepted.'));
        } elseif ($invitation->code != $code) {
            return to_route('threads')->with('error', __('Invitation code is invalid.'));
        }
        $this->form['code'] = $code;
        $this->invitation = $invitation;
    }

    public function render()
    {
        return view('livewire.invitation.accept')->layoutData(['title' => __('Join us')]);
    }

    protected function rules()
    {
        return [
            'form.name'     => ['required', 'string', 'max:255'],
            'form.username' => ['required', 'string', 'max:25', 'unique:users,username'],
            'form.email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'form.password' => $this->passwordRules(),
            'form.terms'    => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ];
    }
}
