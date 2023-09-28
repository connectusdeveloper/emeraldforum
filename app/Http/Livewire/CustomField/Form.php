<?php

namespace App\Http\Livewire\CustomField;

use WireUi\Traits\Actions;
use App\Models\CustomField;
use Illuminate\Validation\Rule;
use Livewire\{Component, WithFileUploads};

class Form extends Component
{
    use Actions;
    use WithFileUploads;

    public CustomField $custom_field;
    public $image;

    public function mount(?CustomField $custom_field)
    {
        $this->custom_field = $custom_field;
        if (!$this->custom_field->id) {
            if (auth()->user()->cant('create-custom-fields')) {
                return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
            }
            $this->custom_field->type = 'Text';
            $this->custom_field->show = true;
            $this->custom_field->active = true;
            $this->custom_field->required = true;
        } elseif (auth()->user()->cant('update-custom-fields')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function render()
    {
        return view('livewire.custom_field.form')->layoutData([
            'title' => $this->custom_field->id ? __('Edit Custom Field') : __('New Custom Field'),
        ]);
    }

    public function save()
    {
        $this->validate();
        $this->custom_field->save();
        return to_route('custom_fields')->with('message', __('Custom field has been successfully saved.'));
    }

    protected function rules()
    {
        return [
            'custom_field.name'        => 'required|min:3|max:30|unique:custom_fields,name,' . $this->custom_field->id,
            'custom_field.description' => 'nullable|string|max:160',
            'custom_field.type'        => 'required|string|in:Text,Date,Select,Checkbox,Radio,Textarea',
            'custom_field.models'      => 'required|array|min:1',
            'custom_field.options'     => ['nullable', Rule::requiredIf(fn () => in_array($this->custom_field->type, ['Select', 'Checkbox', 'Radio']))],
            'custom_field.order_no'    => 'nullable|integer',
            'custom_field.required'    => 'nullable|boolean',
            'custom_field.active'      => 'nullable|boolean',
            'custom_field.show'        => 'nullable|boolean',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'custom_field.name'        => __('name'),
            'custom_field.description' => __('description'),
            'custom_field.type'        => __('type'),
            'custom_field.models'      => __('models'),
            'custom_field.options'     => __('options'),
            'custom_field.order_no'    => __('order no'),
            'custom_field.required'    => __('required'),
            'custom_field.active'      => __('active'),
            'custom_field.show'        => __('show'),
        ];
    }
}
