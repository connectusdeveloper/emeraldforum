<?php

namespace App\Http\Livewire\KnowledgeBase;

use WireUi\Traits\Actions;
use Livewire\{Component, WithFileUploads};
use App\Models\{CustomField, KBCategory, KnowledgeBase};

class Form extends Component
{
    use Actions;
    use WithFileUploads;

    public $custom_fields;
    public $extra_attributes;
    public $image;
    public KnowledgeBase $knowledge_base;

    public function mount(?KnowledgeBase $knowledgebase)
    {
        $this->knowledge_base = $knowledgebase;
        if (!$this->knowledge_base->id) {
            if (auth()->user()->cant('create-knowledgebase')) {
                return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
            }
            $this->knowledge_base->active = true;
        } elseif (auth()->user()->cant('update-knowledgebase')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $this->custom_fields = CustomField::ofModel('KnowledgeBase')->get();
        $this->extra_attributes = $this->knowledge_base->extra_attributes->toArray();
        foreach ($this->custom_fields as $custom_field) {
            $this->extra_attributes[$custom_field->name] ??= null;
        }
    }

    public function render()
    {
        return view('livewire.knowledgebase.form', [
            'k_b_categories' => KBCategory::tree(['id', 'name', 'slug', 'k_b_category_id'])
        ])->layoutData(['title' => ($this->knowledge_base ?? null) ? __('Edit Knowledge Base') : __('New Knowledge Base')]);
    }

    public function save()
    {
        $this->validate();
        $categories = get_id_with_parents($this->knowledge_base->k_b_category_id, [], KBCategory::class, 'k_b_category_id');
        $this->knowledge_base->extra_attributes->set($this->extra_attributes);
        $this->knowledge_base->save();
        $this->knowledge_base->KBCategories()->sync($categories);
        return to_route('knowledgebase')->with('message', __('Knowledge Base has been successfully saved.'));
    }

    protected function rules()
    {
        return [
            'knowledge_base.title'           => 'required|min:5|max:60',
            'knowledge_base.slug'            => 'nullable|alpha_dash|max:60|unique:knowledge_base,slug,' . $this->knowledge_base->id,
            'knowledge_base.body'            => 'required|min:5',
            'knowledge_base.order_no'        => 'nullable|integer',
            'knowledge_base.k_b_category_id' => 'required',
            'knowledge_base.active'          => 'nullable|boolean',
            'extra_attributes.*'             => [function ($attribute, $value, $fail) {
                $attribute = explode('[', explode('.', $attribute)[1])[0];
                $field = $this->custom_fields->where('name', $attribute)->first();
                if ($field?->required && empty($value)) {
                    $fail(__('validation.required', ['attribute' => str($attribute)->lower()]));
                }
            }],
        ];
    }

    protected function validationAttributes()
    {
        return [
            'knowledge_base.title'           => __('title'),
            'knowledge_base.slug'            => __('slug'),
            'knowledge_base.body'            => __('body'),
            'knowledge_base.order_no'        => __('order no'),
            'knowledge_base.k_b_category_id' => __('category'),
            'knowledge_base.active'          => __('active'),
        ];
    }
}
