<?php

namespace App\Http\Livewire\KnowledgeBase;

use Livewire\{Component, WithPagination};
use App\Models\{KBCategory, KnowledgeBase};

class PublicList extends Component
{
    use WithPagination;

    public $k_b_category;

    public function mount(KBCategory $k_b_category)
    {
        if (!(site_config('knowledgebase') ?? null)) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $this->k_b_category = $k_b_category;
    }

    public function render()
    {
        // $knowkedge_base = KnowledgeBase::query()->active();
        // if ($this->k_b_category->id) {
        //     $knowkedge_base->whereRelation('KBCategories', 'id', $this->k_b_category->id);
        // }

        return view('livewire.knowledgebase.list', [
            'categories' => KBCategory::with(['knowledgeBase' => fn ($q) => $q->latest()->limit(3)])->main()->orderBy('order_no', 'asc')->latest()->paginate()
        ])->layout('layouts.public', ['title' => __('Knowledge Base')]);
    }
}
