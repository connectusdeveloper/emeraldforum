<?php

namespace App\Http\Livewire\KnowledgeBase\Category;

use App\Models\KBCategory;
use WireUi\Traits\Actions;
use Livewire\{Component, WithFileUploads};

class Form extends Component
{
    use Actions;
    use WithFileUploads;

    public $image;
    public KBCategory $k_b_category;

    public function mount(?KBCategory $k_b_category)
    {
        $this->k_b_category = $k_b_category;
        if (!$this->k_b_category->id) {
            if (auth()->user()->cant('create-knowledgebase')) {
                return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
            }
            $this->k_b_category->active = true;
        } elseif (auth()->user()->cant('update-knowledgebase')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function render()
    {
        return view('livewire.knowledgebase.category.form', [
            'mainCategories' => KBCategory::get(['id', 'name']),
        ])->layoutData([
            'title' => $this->k_b_category->id ? __('Edit knowledge base category') : __('New knowledge base category'),
        ]);
    }

    public function save()
    {
        $this->validate();
        if ($this->image) {
            $this->k_b_category->image = $this->image->store('images', 'site');
        }
        $this->k_b_category->save();
        $this->emit('saved');
        cache()->forget('kbCategoriesMenu');
        $this->notification()->success(
            $title = __('Saved!'),
            $description = __('Knowledge base category has been successfully saved.')
        );
        return redirect()->route('knowledgebase.categories');
    }

    protected function rules()
    {
        return [
            'k_b_category.name'            => 'required|min:3|max:60',
            'k_b_category.title'           => 'required|min:5|max:60',
            'k_b_category.slug'            => 'nullable|alpha_dash|max:60|unique:categories,slug,' . $this->k_b_category->id,
            'k_b_category.description'     => 'required|string|max:160',
            'k_b_category.k_b_category_id' => 'nullable',
            'k_b_category.order_no'        => 'nullable|integer',
            'k_b_category.active'          => 'nullable|boolean',
            'k_b_category.private'         => 'nullable|boolean',
            'image'                        => 'nullable|image|max:1024',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'k_b_category.name'            => __('name'),
            'k_b_category.title'           => __('title'),
            'k_b_category.slug'            => __('slug'),
            'k_b_category.description'     => __('description'),
            'k_b_category.k_b_category_id' => __('category_id'),
            'k_b_category.order_no'        => __('order no'),
            'k_b_category.active'          => __('active'),
            'k_b_category.private'         => __('private'),
        ];
    }
}
