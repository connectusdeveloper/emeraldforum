<?php

namespace App\Http\Livewire\Faq;

use WireUi\Traits\Actions;
use Livewire\{Component, WithFileUploads};
use App\Models\{CustomField, Faq, FaqCategory};

class Form extends Component
{
    use Actions;
    use WithFileUploads;

    public $custom_fields;
    public $extra_attributes;
    public Faq $faq;
    public $image;

    public function mount(?Faq $faq)
    {
        $this->faq = $faq;
        if (!$this->faq->id) {
            if (auth()->user()->cant('create-faqs')) {
                return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
            }
            $this->faq->active = true;
        } elseif (auth()->user()->cant('update-faqs')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $this->custom_fields = CustomField::ofModel('Faq')->get();
        $this->extra_attributes = $this->faq->extra_attributes->toArray();
        foreach ($this->custom_fields as $custom_field) {
            $this->extra_attributes[$custom_field->name] ??= null;
        }
    }

    public function render()
    {
        return view('livewire.faq.form', [
            'faq_categories' => FaqCategory::tree(['id', 'name', 'slug', 'faq_category_id'])
        ])->layoutData(['title' => ($this->faq ?? null) ? __('Edit FAQ') : __('New FAQ')]);
    }

    public function save()
    {
        $this->validate();
        $categories = get_id_with_parents($this->faq->faq_category_id, [], FaqCategory::class, 'faq_category_id');
        $this->faq->extra_attributes->set($this->extra_attributes);
        $this->faq->save();
        $this->faq->FaqCategories()->sync($categories);
        return to_route('faqs')->with('message', __('FAQ has been successfully saved.'));
    }

    protected function rules()
    {
        return [
            'faq.question'        => 'required|min:5|max:60',
            'faq.slug'            => 'nullable|alpha_dash|max:60|unique:faqs,slug,' . $this->faq->id,
            'faq.answer'          => 'required|min:5',
            'faq.order_no'        => 'nullable|integer',
            'faq.faq_category_id' => 'required',
            'faq.active'          => 'nullable|boolean',
            'extra_attributes.*'  => [function ($attribute, $value, $fail) {
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
            'faq.question'        => __('title'),
            'faq.slug'            => __('slug'),
            'faq.answer'          => __('body'),
            'faq.order_no'        => __('order no'),
            'faq.faq_category_id' => __('category'),
            'faq.active'          => __('active'),
        ];
    }
}
