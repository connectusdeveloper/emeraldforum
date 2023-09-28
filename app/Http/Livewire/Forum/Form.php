<?php

namespace App\Http\Livewire\Forum;

use App\Models\Role;
use WireUi\Traits\Actions;
use App\Models\{CustomField, Thread};
use Livewire\{Component, WithFileUploads};

class Form extends Component
{
    use Actions;
    use WithFileUploads;

    public $attachments;
    public $custom_fields;
    public $extra_attributes;
    public $image;
    public $tags = [];
    public Thread $thread;

    public function mount(?Thread $thread)
    {
        $user = auth()->user();
        if (!$user) {
            return to_route('login');
        }
        $this->thread = $thread;
        if (!$this->thread->id) {
            if (auth()->user()->cant('create-threads')) {
                return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
            }
            $this->thread->active = true;
        } elseif (auth()->user()->cant('update-threads')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $this->custom_fields = CustomField::ofModel('Thread')->get();
        $this->extra_attributes = $this->thread->extra_attributes->toArray();
        foreach ($this->custom_fields as $custom_field) {
            $this->extra_attributes[$custom_field->name] ??= null;
        }
    }

    public function render()
    {
        return view('livewire.forum.form', ['roles' => Role::all()])
            ->layoutData(['title' => $this->thread->id ? __('Edit Thread') : __('New Thread')]);
    }

    public function save()
    {
        if (auth()->guest()) {
            return to_route('login');
        }
        $this->validate();
        $updating = $this->thread->id;
        if ($this->image) {
            $this->thread->image = $this->image->store('images', 'site');
        }
        if (auth()->user()->cant('meta-tags')) {
            $this->thread->description = str($this->thread->body)->limit(159);
        }
        if (auth()->user()->cant('group-permissions')) {
            $this->thread->group = null;
        }
        $this->thread = check_banned_words($this->thread);
        $categories = get_id_with_parents($this->thread->category_id);
        $this->thread->extra_attributes->set($this->extra_attributes);
        $this->thread->description = strip_tags($this->thread->description);
        $this->thread->approved = !require_approval();
        $this->thread->save();
        $this->thread->categories()->sync($categories);
        $this->thread->attachTags($this->tags);
        if ($this->attachments) {
            $this->thread->saveAttachments($this->attachments);
        }
        if ($updating) {
            activity()->causedBy(auth()->user())->performedOn($this->thread)->event('updated')->log('Updated the thread');
        }
        cache()->flush();
        $this->emit('saved');
        return to_route('threads.show', $this->thread->slug)->with('message', __('Thread has been successfully saved.'));
    }

    protected function rules()
    {
        return [
            'thread.title'           => 'required|min:5|max:60',
            'thread.slug'            => 'nullable|alpha_dash|max:60|unique:threads,slug,' . $this->thread->id,
            'thread.description'     => [auth()->user()->can('meta-tags') ? 'required' : 'nullable', 'max:160'],
            'thread.body'            => 'required|string',
            'thread.category_id'     => 'required',
            'thread.active'          => 'nullable|boolean',
            'thread.sticky'          => 'nullable|boolean',
            'thread.sticky_category' => 'nullable|boolean',
            'thread.private'         => 'nullable|boolean',
            'thread.noindex'         => 'nullable|boolean',
            'thread.nofollow'        => 'nullable|boolean',
            'thread.group'           => 'nullable',
            'tags'                   => 'nullable|array',
            'image'                  => 'nullable|image|max:1024',
            'extra_attributes.*'     => [function ($attribute, $value, $fail) {
                $attribute = explode('.', $attribute)[1];
                $field = $this->custom_fields->where('name', $attribute)->first();
                if ($field->required && empty($value)) {
                    $fail(__('validation.required', ['attribute' => str($attribute)->lower()]));
                }
            }],
        ];
    }
}
