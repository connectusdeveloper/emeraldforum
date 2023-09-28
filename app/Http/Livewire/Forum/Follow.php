<?php

namespace App\Http\Livewire\Forum;

use Livewire\Component;
use WireUi\Traits\Actions;

class Follow extends Component
{
    use Actions;

    public $user;

    public function follow()
    {
        auth()->user()->follow($this->user);
        $this->notification()->success(
            $title = __('Followed!'),
            $description = __('You have started following :user.', ['user' => $this->user->displayName])
        );
    }

    public function mount($user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.forum.follow');
    }

    public function unfollow()
    {
        auth()->user()->unfollow($this->user);
        $this->notification()->success(
            $title = __('Followed!'),
            $description = __('You have stopped following :user.', ['user' => $this->user->displayName])
        );
    }
}
