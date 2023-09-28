<?php

namespace App\Http\Livewire\Forum;

use WireUi\Traits\Actions;
use Livewire\{Component, WithPagination};
use App\Notifications\SendReplyVerificationEmail;
use App\Models\{CustomField, Thread as ThreadsModal};

class Thread extends Component
{
    use Actions;
    use ThreadActions;
    use WithPagination;

    public $custom_fields;
    public $extra_attributes = [];
    public $form = ['body' => '', 'guest_name' => '', 'guest_email' => ''];
    public ThreadsModal $thread;
    protected $settings;

    public function mount(ThreadsModal $thread)
    {
        if (!request()->has('page')) {
            $replies = $this->thread->replies()->oldest()->paginate();
            if ($replies->lastPage() > 1) {
                return to_route('threads.show', ['thread' => $thread->slug, 'page' => $replies->lastPage()]);
            }
        }
        $this->thread = $thread;
        if (!$this->thread->approved && (!auth()->user() || auth()->user()?->cant('approve'))) {
            return to_route('threads')->with('info', __('The thread is under review.'));
        }
        $this->settings = site_config();
        $this->thread->increment('views');
        $this->custom_fields = CustomField::ofModel('Reply')->get();
        // $this->extra_attributes = $this->faq->extra_attributes->toArray();
        foreach ($this->custom_fields as $custom_field) {
            $this->extra_attributes[$custom_field->name] ??= null;
        }
    }

        public function render()
        {
            $this->emit('page-changed');
            $this->thread = $this->thread->loadMissing(['categories', 'user', 'acceptedReply']);
            return view('livewire.forum.thread', [
                'replies' => $this->thread->replies()->with(['flag', 'user'])->oldest()->paginate()
            ])->layout('layouts.public');
        }

    public function reply()
    {
        if (auth()->user()->cant('create-replies')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $this->validate();
        $reply = $this->thread->replies()->create(check_banned_words($this->form, true));
        $reply->extra_attributes->set($this->extra_attributes);
        $reply->saveQuietly();
        $message = __('Your reply has been successfully saved.');
        if (auth()->guest()) {
            $reply->notify(new SendReplyVerificationEmail($reply));
            $message = __('We have sent you email, please confirm your reply.');
        }
        return to_route('threads.show', $this->thread->slug)->with('message', $message);
    }

    protected function rules()
    {
        return [
            'form.body'          => 'required',
            'form.guest_name'    => auth()->guest() ? 'required' : 'nullable',
            'form.guest_email'   => auth()->guest() ? 'required' : 'nullable',
            'extra_attributes.*' => [function ($attribute, $value, $fail) {
                $attribute = explode('.', $attribute)[1];
                $field = $this->custom_fields->where('name', $attribute)->first();
                if ($field->required && empty($value)) {
                    $fail(__('validation.required', ['attribute' => str($attribute)->lower()]));
                }
            }],
        ];
    }
}
