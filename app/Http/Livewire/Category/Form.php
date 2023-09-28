<?php

namespace App\Http\Livewire\Category;

use App\Models\Category;
use WireUi\Traits\Actions;
use Livewire\{Component, WithFileUploads};

class Form extends Component
{
    use Actions;
    use WithFileUploads;

    public Category $category;

    public function mount(?Category $category)
    {
        $this->category = $category;
        if (!$this->category->id) {
            if (auth()->user()->cant('create-categories')) {
                return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
            }
            $this->category->active = true;
        } elseif (auth()->user()->cant('update-categories')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function render()
    {
        return view('livewire.category.form', [
            'mainCategories' => Category::get(['id', 'name']),
        ])->layoutData([
            'title' => $this->category->id ? __('Edit Category') : __('New Category'),
        ]);
    }

    public function save()
    {
        $this->validate();
        $this->category->save();
        $this->emit('saved');
        cache()->forget('categoriesMenu');
        $this->notification()->success(
            $title = __('Saved!'),
            $description = __('Category has been successfully saved.')
        );
        return redirect()->route('categories');
    }

    protected function rules()
    {
        return [
            'category.name'        => 'required|min:3|max:60',
            'category.title'       => 'required|min:5|max:60',
            'category.slug'        => 'nullable|alpha_dash|max:60|unique:categories,slug,' . $this->category->id,
            'category.description' => 'required|string|max:160',
            'category.category_id' => 'nullable',
            'category.order_no'    => 'nullable|integer',
            'category.active'      => 'nullable|boolean',
            'category.private'     => 'nullable|boolean',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'category.name'        => __('name'),
            'category.title'       => __('title'),
            'category.slug'        => __('slug'),
            'category.description' => __('description'),
            'category.category_id' => __('category_id'),
            'category.order_no'    => __('order no'),
            'category.active'      => __('active'),
            'category.private'     => __('private'),
        ];
    }
}
