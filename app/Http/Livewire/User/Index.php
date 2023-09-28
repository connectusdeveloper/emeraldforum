<?php

namespace App\Http\Livewire\User;

use WireUi\Traits\Actions;
use App\Models\{Badge, User};
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use Actions;
    use WithPagination;

    public $active;
    public $banned;
    public $user_badges = [];
    protected $queryString = ['active', 'banned'];

    public function assignBadges($user_id)
    {
        if (empty($this->user_badges)) {
            $this->notification()->error(
                $title = __('Error!'),
                $description = __('Please select at least one badge.')
            );
            return false;
        }
        if (auth()->user()->can('assign-badges')) {
            $user = User::findOrFail($user_id);
            $user->badges()->attach($this->user_badges);
            $this->notification()->success(
                $title = __('Success!'),
                $description = __(':record has been updated.', ['record' => _('User')])
            );
        }
    }

    public function mount()
    {
        if (auth()->user()->cant('read-users')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function removeRecord($id)
    {
        if (auth()->user()->cant('delete-users')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $user = User::findOrFail($id);
        if ($user->threads()->count() || $user->replies()->count()) {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('Failed to delete :record. It has threads or replies.', ['record' => _('Role')])
            );
            return false;
        }
        if ($user->delete()) {
            $this->notification()->success(
                $title = __('Success!'),
                $description = __(':record has been deleted.', ['record' => _('User')])
            );
        } else {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('Failed to delete :record.', ['record' => _('User')])
            );
        }
    }

        public function render()
        {
            $users = User::query()->with('roles:id,name');
            if ('yes' == $this->active) {
                $users->active();
            } elseif ('no' == $this->active) {
                $users->inactive();
            }
            if ('yes' == $this->banned) {
                $users->banned();
            } elseif ('no' == $this->banned) {
                $users->notBanned();
            }
            return view('livewire.user.index', [
                'users' => $users->latest()->paginate(), 'badges' => Badge::latest()->get(),
            ])->layoutData(['title' => __('Users')]);
        }
}
