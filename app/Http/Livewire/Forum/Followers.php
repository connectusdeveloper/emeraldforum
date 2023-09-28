<?php

namespace App\Http\Livewire\Forum;

use App\Models\User;
use Livewire\Component;

class Followers extends Component
{
    public User $user;

    public function mount($user = null)
    {
        $this->user = $user?->id ? $user : auth()->user();
    }

    public function render()
    {
        return view('livewire.forum.followers', [
            'followers' => $this->user->followers()->paginate(),
        ])->layout('layouts.public');
    }
}
