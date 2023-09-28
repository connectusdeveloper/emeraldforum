<?php

namespace App\Http\Livewire\Forum;

use Livewire\Component;
use WireUi\Traits\Actions;

class Message extends Component
{
    use Actions;

    public $message;
    public $show = false;
    public $user_id;
    protected $rules = ['message' => 'required', 'user_id' => 'required'];

    public function render()
    {
        return view('livewire.forum.message');
    }

    public function send()
    {
        $this->validate();
        auth()->user()->sendMessage(['user_id' => $this->user_id, 'message' => $this->message]);
        $this->emit('message-sent');
        $this->notification()->success(
            $title = __('Sent!'),
            $description = __('Your message has been sent.')
        );
    }
}
