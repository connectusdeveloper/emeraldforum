<?php

namespace App\Http\Livewire\Role;

use App\Models\Role;
use Livewire\Component;
use WireUi\Traits\Actions;

class Index extends Component
{
    use Actions;

    public function mount()
    {
        if (auth()->user()->cant('read-roles')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function removeRecord($id)
    {
        if (auth()->user()->cant('delete-roles')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }

        $role = Role::findOrFail($id);
        $reserved = ['super', 'admin', 'member'];
        if (in_array($role->name, $reserved)) {
            return redirect()->to(url()->previous())->with('error', __('You should not delete reserved roles.'));
        }

        if (User::role($role->name)->count()) {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('Failed to delete :record. It is attached to users.', ['record' => _('Role')])
            );

            return false;
        }
        if ($role->delete()) {
            $this->notification()->success(
                $title = __('Success!'),
                $description = __(':record has been deleted.', ['record' => _('Role')])
            );
        } else {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('Failed to delete :record.', ['record' => _('Role')])
            );
        }
    }

    public function render()
    {
        return view('livewire.role.index', ['roles' => Role::latest()->paginate()])
            ->layoutData(['title' => __('Roles')]);
    }
}
