<?php

namespace App\Http\Livewire\Forum;

use Livewire\Component;
use WireUi\Traits\Actions;
use App\Models\Reply as ModelsReply;

class Reply extends Component
{
    use Actions;

    public $body;

    public $edit;

    public $last;

    public ModelsReply $reply;

    public $showAccept;

    protected $rules = ['body' => 'required|min:2'];

    protected $settings;

    public function accept()
    {
        $user = auth()->user();
        if (! $user) {
            return to_route('login');
        } elseif ($user->id != $this->reply->thread->user_id) {
            $this->notification()->info(
                $title = __('Failed!'),
                $description = __('You do not have permissions to perform this action.')
            );

            return false;
        }

        $this->reply->thread->replies()->accepted()->update(['accepted' => 0]);
        $this->reply->accepted = 1;
        $this->reply->save();
        activity()->causedBy($user)->performedOn($this->reply->thread)->event('accepted-answer')->log('Accepted the reply on thread');
        $this->notification()->success(
            $title = __('Saved!'),
            $description = __('You have accepted the answer.')
        );
    }

    public function dislike()
    {
        $user = auth()->user();
        if (! $user) {
            return to_route('login');
        }
        $vote = $this->reply->votes()->by($user)->first();
        if ($vote) {
            if (! ($this->settings['voting'] ?? null)) {
                $this->notification()->info(
                    $title = __('Voted!'),
                    $description = __('You have already voted the reply.')
                );

                return false;
            }
            if ($vote->up_vote) {
                $this->reply->decrement('up_votes');
            } elseif ($vote->down_vote) {
                $this->reply->decrement('down_votes');
            }
            $vote->update(['up_vote' => 0, 'down_vote' => 1]);
        } else {
            $this->reply->votes()->create(['user_id' => $user->id, 'up_vote' => 0, 'down_vote' => 1]);
        }

        $this->reply->increment('down_votes');
        activity()->causedBy($user)->performedOn($this->reply->thread)->event('disliked')->log('Dislike the reply on thread');
        $this->notification()->success(
            $title = __('Saved!'),
            $description = __('You have disliked the reply.')
        );
    }

    public function editNow()
    {
        if (! $this->reply->canEdit()) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $this->edit = true;
        $this->body = $this->reply->body;
    }

    public function like()
    {
        $user = auth()->user();
        if (! $user) {
            return to_route('login');
        }
        $vote = $this->reply->votes()->by($user)->first();
        if ($vote) {
            if (! ($this->settings['voting'] ?? null)) {
                $this->notification()->info(
                    $title = __('Voted!'),
                    $description = __('You have already voted the reply.')
                );

                return false;
            }
            if ($vote->up_vote) {
                $this->reply->decrement('up_votes');
            } elseif ($vote->down_vote) {
                $this->reply->decrement('down_votes');
            }
            $vote->update(['up_vote' => 1, 'down_vote' => 0]);
        } else {
            $this->reply->votes()->create(['user_id' => $user->id, 'up_vote' => 1, 'down_vote' => 0]);
        }

        $this->reply->increment('up_votes');
        activity()->causedBy($user)->performedOn($this->reply->thread)->event('liked')->log('Like the reply on thread');
        $this->notification()->success(
            $title = __('Saved!'),
            $description = __('You have liked the reply.')
        );
    }

    public function mount(ModelsReply $reply, $last, $showAccept = true)
    {
        $this->last = $last;
        $this->reply = $reply;
        $this->showAccept = $showAccept;
        $this->settings = site_config();
    }

    public function remove()
    {
        if (! $this->reply->canDelete()) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $user = auth()->user();
        if (! $user) {
            return to_route('login');
        }

        if (! $user->hasRole('super') && (! $user->can('delete-replies') || $user->id !== $this->reply->user_id)) {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('You cannot delete the reply.')
            );

            return false;
        }

        $this->reply->delete();

        return to_route('threads.show', $this->reply->thread->slug)->with('message', __('The reply has been deleted.'));
    }

    public function render()
    {
        return view('livewire.forum.reply');
    }

    public function update()
    {
        if (auth()->guest()) {
            return to_route('login');
        }
        if (! $this->reply->canEdit()) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $this->validate();
        $this->reply->body = $this->body;
        $this->reply->save();
        $this->body = null;
        $this->edit = false;
        activity()->causedBy(auth()->user())->performedOn($this->reply)->event('updated-reply')->log('Updated the reply on thread');
        $this->notification()->success(
            $title = __('Success!'),
            $description = __('Your reply has been successfully saved.')
        );
    }
}
