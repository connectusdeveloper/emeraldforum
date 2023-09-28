<?php

namespace App\Http\Livewire\Forum;

use Livewire\Component;
use WireUi\Traits\Actions;
use App\Models\Conversation;
use App\Jobs\SendNotifications;

class Conversations extends Component
{
    use Actions;

    public $message;
    public $selected;
    public $settings;
    protected $rules = ['message' => 'required'];

    public function mount(Conversation $conversation)
    {
        $this->settings = site_config();
        $this->selected = $conversation;
        $this->selected?->messages()->ofUser()->unseen()->update(['seen' => 1]);
    }

    public function render()
    {
        return view('livewire.forum.conversations', [
            'conversations' => auth()->user()->conversations(),
            'messages'      => $this->selected ? $this->selected->messages()->latest('id')->paginate() : [],
        ])->layout('layouts.app');
    }

    public function reply()
    {
        if ($this->selected) {
            $this->selected->messages()->create(['user_id' => auth()->id(), 'body' => $this->message]);
            SendNotifications::dispatchAfterResponse(auth()->user(), $this->selected, 'conversation-replied');
            $this->message = '';
            $this->emit('message-sent');
            $this->notification()->success(
                $title = __('Sent!'),
                $description = __('Your message has been sent.')
            );
        }
    }

    public function selectConversation($id)
    {
        $this->selected = Conversation::find($id);
        $this->selected?->messages()->ofUser()->unseen()->update(['seen' => 1]);
        $this->emit('conversation-changed');
    }
}
