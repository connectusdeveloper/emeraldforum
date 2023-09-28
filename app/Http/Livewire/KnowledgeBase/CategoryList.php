<?php

namespace App\Http\Livewire\KnowledgeBase;

use Livewire\{Component, WithPagination};
use App\Models\{KBCategory, KnowledgeBase};

class CategoryList extends Component
{
    use WithPagination;

    public $k_b_category;

    public function mount(?KBCategory $k_b_category)
    {
        if (!(site_config('knowledgebase') ?? null)) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $this->k_b_category = $k_b_category;
    }

    public function render()
    {
        $knowledge_base = KnowledgeBase::query()->active();
        if ($this->k_b_category->id) {
            $knowledge_base->whereRelation('KBCategories', 'id', $this->k_b_category->id);
        }
        return view('livewire.knowledgebase.category-list', ['knowledge_base' => $knowledge_base->latest()->paginate()])->layout('layouts.public');
    }
}
