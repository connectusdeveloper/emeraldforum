<?php

namespace App\Http\Livewire\Faq\Category;

use WireUi\Traits\Actions;
use App\Models\FaqCategory;
use Livewire\{Component, WithFileUploads};

class Form extends Component
{
    use Actions;
    use WithFileUploads;

    public FaqCategory $faq_category;
    public $image;

    public function mount(?FaqCategory $faq_category)
    {
        $this->faq_category = $faq_category;
        if (!$this->faq_category->id) {
            if (auth()->user()->cant('create-faqs')) {
                return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
            }
            $this->faq_category->active = true;
        } elseif (auth()->user()->cant('update-faqs')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function render()
    {
        return view('livewire.faq.category.form', [
            'mainCategories' => FaqCategory::get(['id', 'name']),
        ])->layoutData([
            'title' => $this->faq_category->id ? __('Edit FAQ Category') : __('New FAQ Category'),
        ]);
    }

    public function save()
    {
        $this->validate();
        if ($this->image) {
            $this->faq_category->image = $this->image->store('images');
        }
        $this->faq_category->save();
        $this->emit('saved');
        cache()->forget('faqCategoriesMenu');
        $this->notification()->success(
            $title = __('Saved!'),
            $description = __('FAQ Category has been successfully saved.')
        );
        return redirect()->route('faq.categories');
    }

    protected function rules()
    {
        return [
            'faq_category.name'            => 'required|min:3|max:60',
            'faq_category.title'           => 'required|min:5|max:60',
            'faq_category.slug'            => 'nullable|alpha_dash|max:60|unique:categories,slug,' . $this->faq_category->id,
            'faq_category.description'     => 'required|string|max:160',
            'faq_category.faq_category_id' => 'nullable',
            'faq_category.order_no'        => 'nullable|integer',
            'faq_category.active'          => 'nullable|boolean',
            'faq_category.private'         => 'nullable|boolean',
            'image'                        => 'nullable|image|max:1024',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'faq_category.name'            => __('name'),
            'faq_category.title'           => __('title'),
            'faq_category.slug'            => __('slug'),
            'faq_category.description'     => __('description'),
            'faq_category.faq_category_id' => __('category_id'),
            'faq_category.order_no'        => __('order no'),
            'faq_category.active'          => __('active'),
            'faq_category.private'         => __('private'),
        ];
    }
}
