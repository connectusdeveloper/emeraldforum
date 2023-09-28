<?php

namespace App\Http\Livewire\User;

use WireUi\Traits\Actions;
use App\Models\{Role, User};
use Illuminate\Support\Facades\Hash;
use Livewire\{Component, WithFileUploads};

class Form extends Component
{
    use Actions;
    use WithFileUploads;

    public $image;
    public $password;
    public $password_confirmation;
    public $roles = [];
    public User $user;

    public function mount(?User $user)
    {
        $this->user = $user;
        if (!$this->user->id) {
            if (auth()->user()->cant('create-users')) {
                return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
            }
            $this->user->active = true;
        } else {
            if (auth()->user()->cant('update-users')) {
                return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
            }
            $this->roles = $this->user->getRoleNames();
        }
    }

    public function render()
    {
        return view('livewire.user.form', ['all_roles' => Role::get(['id', 'name'])])->layoutData([
            'title' => $this->user->id ? __('Edit User') : __('New User'),
        ]);
    }

    public function save()
    {
        $this->validate();
        if ($this->password) {
            $this->user->password = Hash::make($this->password);
        } else {
            unset($this->user->password);
        }
        $this->user->save();
        $this->user->syncRoles($this->roles);
        return to_route('users')->with('message', __('User has been successfully saved.'));
    }

    protected function rules()
    {
        return [
            'user.name'             => 'required|min:3|max:60',
            'user.username'         => 'required|regex:/^[A-Za-z0-9._-]+$/|max:25|unique:users,username,' . $this->user->id,
            'user.email'            => 'required|email|max:25|unique:users,email,' . $this->user->id,
            'password'              => $this->user->id ? 'nullable' : 'required|string|confirmed',
            'password_confirmation' => $this->user->id ? 'nullable' : 'required',
            'roles'                 => 'required',
            'user.active'           => 'nullable|boolean',
            'user.banned'           => 'nullable|boolean',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'user.name'     => __('name'),
            'user.email'    => __('email'),
            'user.username' => __('username'),
        ];
    }
}
