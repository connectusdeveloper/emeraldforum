<?php

namespace App\Http\Livewire\Invitation;

use App\Models\User;
use Livewire\Component;
use App\Models\Invitation;
use WireUi\Traits\Actions;
use App\Notifications\SendInvitation;

class Index extends Component
{
    use Actions;

    public Invitation $invitation;

    protected $rules = ['invitation.email' => 'required|email'];

    public function mount(?Invitation $invitation)
    {
        if (auth()->user()?->cant('invitations')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }

        $this->invitation = $invitation;
    }

    public function removeRecord($id)
    {
        if (auth()->user()->cant('invitations')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }

        $invitation = Invitation::findOrFail($id);
        if ($invitation->accepted_at) {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('Invitation is already accepted and cannot be removed.')
            );

            return false;
        }

        if ($invitation->delete()) {
            $this->notification()->success(
                $title = __('Success!'),
                $description = __(':record has been deleted.', ['record' => _('Invitation')])
            );
        } else {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('Failed to delete :record.', ['record' => _('Invitation')])
            );
        }
    }

    public function render()
    {
        return view('livewire.invitation.index', [
            'invitations' => Invitation::where('user_id', auth()->id())->latest()->paginate(),
        ])->layoutData(['title' => __('Knowledge base categories')]);
    }

    public function send()
    {
        $this->validate();
        if (User::where('email', $this->invitation->email)->exists()) {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('The user is already a member.')
            );

            return false;
        }

        $this->invitation->save();
        $this->invitation->notify(new SendInvitation());

        return to_route('invitations')->with('message', __('Invitation has been successfully sent.'));
    }
}
