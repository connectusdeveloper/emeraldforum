<?php

namespace App\Http\Livewire\Badge;

use App\Models\Badge;
use WireUi\Traits\Actions;
use Livewire\{Component, WithFileUploads};

class Form extends Component
{
    use Actions;
    use WithFileUploads;

    public Badge $badge;
    public $image;

    public function mount(?Badge $badge)
    {
        $this->badge = $badge;
        if (!$this->badge->id) {
            if (auth()->user()->cant('create-badges')) {
                return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
            }
            $this->badge->active = true;
        } elseif (auth()->user()->cant('update-badges')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function render()
    {
        return view('livewire.badge.form')->layoutData([
            'title' => $this->badge->id ? __('Edit Badge') : __('New Badge'),
        ]);
    }

    public function save()
    {
        $this->validate();
        if ($this->image) {
            $this->badge->image = $this->image->store('images', 'site');
        }
        $this->badge->save();
        $this->emit('saved');
        $this->notification()->success(
            $title = __('Saved!'),
            $description = __('Badge has been successfully saved.')
        );
        return redirect()->route('badges');
    }

    protected function rules()
    {
        return [
            'badge.name'      => 'required|min:3|max:60',
            'badge.css_class' => 'required_if:image,null',
            'image'           => 'bail|required_if:css_class,null|nullable|image|max:200',
            'badge.active'    => 'nullable|boolean',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'badge.name'      => __('name'),
            'badge.active'    => __('active'),
            'badge.css_class' => __('css class'),
        ];
    }
}
