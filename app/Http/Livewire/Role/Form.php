<?php

namespace App\Http\Livewire\Role;

use App\Models\Role;
use Livewire\Component;
use WireUi\Traits\Actions;

class Form extends Component
{
    use Actions;

    public $permissions = [];
    public Role $role;

    public function mount(?Role $role)
    {
        if ($role && 'super' == $role->name) {
            return redirect()->to(url()->previous())->with('error', __('You should not edit super role.'));
        }

        $this->role = $role->load('permissions');
        if (!$this->role->id) {
            if (auth()->user()->cant('create-roles')) {
                return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
            }
            $this->role->active = true;
        } else {
            if (auth()->user()->cant('update-roles')) {
                return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
            }
            $this->permissions = $role->getAllPermissions()->pluck('name')->flip()->transform(fn () => true)->all();
        }
        $this->permissions['read-threads'] = 1;
    }

    public function render()
    {
        return view('livewire.role.form')->layoutData([
            'title' => $this->role->id ? __('Edit Role') : __('New Role'),
        ]);
    }

    public function save()
    {
        $this->validate();
        $this->role->save();
        $this->role->updatePermissions($this->permissions);
        return to_route('roles')->with('message', __('Role has been successfully saved.'));
    }

    protected function rules()
    {
        return [
            'role.name'     => 'required|min:3|max:60',
            'permissions'   => 'required|array',
            'permissions.*' => 'nullable',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'role.name' => __('name'),
        ];
    }
}
