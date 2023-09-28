<?php

namespace App\Http\Livewire\Forum;

use App\Models\User;
use Livewire\Component;

class Members extends Component
{
    public function mount()
    {
        if (!site_config('member_page')) {
            return to_route('threads');
        }
    }

    public function render()
    {
        return view('livewire.forum.members', [
            'members' => User::with('badges')->withCount(['replies', 'threads'])
                ->orderBy('replies_count', 'desc')->orderBy('threads_count', 'desc')->paginate(),
        ])->layout('layouts.public');
    }
}
