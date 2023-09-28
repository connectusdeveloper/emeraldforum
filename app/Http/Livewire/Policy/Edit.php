<?php

namespace App\Http\Livewire\Policy;

use Livewire\Component;
use WireUi\Traits\Actions;
use Illuminate\Support\Facades\File;

class Edit extends Component
{
    use Actions;

    public $form;
    protected $rules = [
        'form.terms'  => 'required',
        'form.policy' => 'required',
    ];

    public function mount()
    {
        $this->form['terms'] = File::get(resource_path('markdown/terms.md'));
        $this->form['policy'] = File::get(resource_path('markdown/policy.md'));
    }

    public function render()
    {
        return view('livewire.policy.edit')->layoutData(['title' => __('Application Settings')]);
    }

    public function save()
    {
        $this->validate();
        File::put(resource_path('markdown/terms.md'), $this->form['terms']);
        File::put(resource_path('markdown/policy.md'), $this->form['policy']);
        return to_route('policies.edit')->with('message', __('Policies has been successfully updated.'));
    }

    protected function validationAttributes()
    {
        return [
            'form.terms'  => __('terms of service'),
            'form.policy' => __('privacy policy'),
        ];
    }
}
