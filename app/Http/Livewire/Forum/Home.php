<?php

namespace App\Http\Livewire\Forum;

use WireUi\Traits\Actions;
use App\Jobs\SendNotifications;
use Livewire\{Component, WithPagination};
use App\Models\{Category, Tag, Thread, User};

class Home extends Component
{
    use Actions;
    use WithPagination;

    public $by;
    public Category $category;
    public $favorites_of;
    public $require_approval;
    public $require_review;
    public $sorting = 'latest';
    public $tag;
    public $trending;
    protected $queryString = ['by', 'favorites_of', 'require_approval', 'require_review', 'tag', 'trending'];

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

        foreach (Thread::notApproved()->cursor() as $thread) {
            $thread->update(['approved' => 1, 'approved_by' => $user->id]);
            SendNotifications::dispatchAfterResponse($user, $thread->load(['approvedBy', 'user'])->refresh(), 'approved');
        }
        return to_route('threads')->with('message', __('All the threads have been approved.'));
    }

    public function mount(?Category $category)
    {
        $this->category = $category;
        $this->sorting = session('sorting', 'latest');
    }

    public function render()
    {
        if ($this->favorites_of && $user = User::withoutGlobalScope('withCounts')->where('username', $this->favorites_of)->first()) {
            $threads = $user->favorites();
        } elseif ($this->tag) {
            $tag = Tag::where('name', $this->tag)->first();
            $threads = $tag->threads();
        } else {
            $threads = Thread::query();
        }

        if ($this->by && $user = User::where('username', $this->by)->first()) {
            $threads->where('user_id', $user->id);
        }
        $loggedIn = auth()->user();
        if ($loggedIn) {
            if ('yes' == $this->require_review && $loggedIn->can('review')) {
                $threads->flagged();
            }
            if ('yes' == $this->require_approval && $loggedIn->can('approve-threads')) {
                $threads->notApproved()->orderBy('approved', 'asc');
            } else {
                $threads->active()->approved();
            }
        } else {
            $threads->active()->approved();
        }
        if ($this->category->id) {
            $threads->orderBy('sticky_category', 'desc')
                ->whereRelation('categories', 'id', $this->category->id);
        } else {
            $threads->orderBy('sticky', 'desc');
        }

        if ('likes' == $this->sorting) {
            $threads->orderBy('up_votes', 'desc');
        } elseif ('replies' == $this->sorting) {
            $threads->orderBy('replies_count', 'desc');
        } else {
            $threads->latest();
        }

        if ($this->trending && 'yes' == $this->trending) {
            $threads->reorder();
            if ($this->category->id) {
                $threads->orderBy('sticky_category', 'desc')
                    ->whereRelation('categories', 'id', $this->category->id);
            } else {
                $threads->orderBy('sticky', 'desc');
            }
            $threads->withCount(['replies as last_week' => fn ($q) => $q->whereBetween('created_at', [now()->subDays(7), now()])])->orderBy('last_week', 'desc');
            $this->sorting = 'trending';
        }

        $this->emit('page-changed');
        session(['sorting' => $this->sorting]);
        return view('livewire.forum.home', [
            'threads' => $threads->forUser()->paginate()->withQueryString()
        ])->layout('layouts.public');
    }
}
