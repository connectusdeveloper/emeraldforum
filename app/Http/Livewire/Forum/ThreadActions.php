<?php

namespace App\Http\Livewire\Forum;

use App\Jobs\SendNotifications;

trait ThreadActions
{
    public function approve()
    {
        if (auth()->user()->cant('approve-threads')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $user = auth()->user();
        if (!$user) {
            return to_route('login');
        } elseif (!$user->can('approve-threads')) {
            return to_route('threads');
        }

        $this->thread->update(['approved' => 1, 'approved_by' => $user->id]);
        SendNotifications::dispatchAfterResponse($user, $this->thread->load(['approvedBy', 'user'])->refresh(), 'approved');
        $this->notification()->success(
            $title = __('Approved!'),
            $description = __('You have approved the thread.')
        );
    }

    public function dislike()
    {
        $user = auth()->user();
        if (!$user) {
            return to_route('login');
        }
        $vote = $this->thread->votes()->by($user)->first();
        if ($vote) {
            if (!($this->settings['voting'] ?? null)) {
                $this->notification()->info(
                    $title = __('Voted!'),
                    $description = __('You have already voted the thread.')
                );
                return false;
            }
            if ($vote->up_vote) {
                $this->thread->decrement('up_votes');
            } elseif ($vote->down_vote) {
                $this->thread->decrement('down_votes');
            }
            $vote->update(['up_vote' => 0, 'down_vote' => 1]);
        } else {
            $this->thread->votes()->create(['user_id' => $user->id, 'up_vote' => 0, 'down_vote' => 1]);
        }

        $this->thread->increment('down_votes');
        activity()->causedBy($user)->performedOn($this->thread)->event('disliked')->log('Dislike the thread');
        $this->notification()->success(
            $title = __('Saved!'),
            $description = __('You have disliked the thread.')
        );
    }

    public function favorite()
    {
        $user = auth()->user();
        if (!$user) {
            return to_route('login');
        }
        $user->favorites()->detach($this->thread->id);
        $user->favorites()->attach($this->thread->id);
        activity()->causedBy($user)->performedOn($this->thread)->event('favorited')->log('Favorited the thread');
        $this->notification()->success(
            $title = __('Saved!'),
            $description = __('You have added the thread to your favorites.')
        );
    }

    public function like()
    {
        $user = auth()->user();
        if (!$user) {
            return to_route('login');
        }
        $vote = $this->thread->votes()->by($user)->first();
        if ($vote) {
            if (!($this->settings['voting'] ?? null)) {
                $this->notification()->info(
                    $title = __('Voted!'),
                    $description = __('You have already voted the thread.')
                );
                return false;
            }
            if ($vote->up_vote) {
                $this->thread->decrement('up_votes');
            } elseif ($vote->down_vote) {
                $this->thread->decrement('down_votes');
            }
            $vote->update(['up_vote' => 1, 'down_vote' => 0]);
        } else {
            $this->thread->votes()->create(['user_id' => $user->id, 'up_vote' => 1, 'down_vote' => 0]);
        }

        $this->thread->increment('up_votes');
        activity()->causedBy($user)->performedOn($this->thread)->event('liked')->log('Like the thread');
        $this->notification()->success(
            $title = __('Saved!'),
            $description = __('You have liked the thread.')
        );
    }

    public function remove()
    {
        if (!$this->thread->canDelete()) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $user = auth()->user();
        if (!$user) {
            return to_route('login');
        }

        if (!$user->hasRole('super') && (!$user->can('delete-threads') || $user->id !== $this->thread->user_id)) {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('You cannot delete the thread.')
            );
            return false;
        }
        $this->thread->flag()->delete();
        $this->thread->replies()->delete();
        $this->thread->favorites()->detach();
        $this->thread->categories()->detach();
        $this->thread->delete();
        // $this->emit('thread-deleted');
        if ($this->thread->flag) {
            return to_route('threads', ['require_review' => 'yes'])->with('message', __('The thread has been deleted.'));
        }
        return to_route('threads')->with('message', __('The thread has been deleted.'));
    }

    public function removeFlag()
    {
        $user = auth()->user();
        if (!$user) {
            return to_route('login');
        } elseif ($user->cant('review')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }

        $this->thread->flag()->delete();
        return to_route('threads', ['require_review' => 'yes'])->with('message', __('You have remove the flag.'));
    }
}
