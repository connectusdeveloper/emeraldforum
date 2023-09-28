<?php

namespace App\Http\Livewire\Badge;

use App\Models\Badge;
use Livewire\Component;
use WireUi\Traits\Actions;

class Index extends Component
{
    use Actions;

    public $users = [];

    public function assignBadge($badge_id)
    {
        if (auth()->user()->cant('assign-badges')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        if (empty($this->users)) {
            $this->notification()->error(
                $title = __('Error!'),
                $description = __('Please select at least one user.')
            );
            return false;
        }
        if (auth()->user()->can('assign-badges')) {
            $badge = Badge::findOrFail($badge_id);
            $badge->users()->attach($this->users);
            $this->notification()->success(
                $title = __('Success!'),
                $description = __(':record has been assigned.', ['record' => _('Badge')])
            );
        }
    }

    public function mount()
    {
        if (auth()->user()->cant('read-badges')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function removeRecord($id)
    {
        if (auth()->user()->cant('delete-badges')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $badge = Badge::findOrFail($id);
        if ($badge->delete()) {
            $this->notification()->success(
                $title = __('Success!'),
                $description = __(':record has been deleted.', ['record' => _('Badge')])
            );
        } else {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('Failed to delete :record.', ['record' => _('Badge')])
            );
        }
    }

    public function render()
    {
        return view('livewire.badge.index', ['badges' => Badge::paginate()])
            ->layoutData(['title' => __('Badges')]);
    }
}
