<?php

namespace App\Http\Livewire\Faq;

use App\Models\{Faq, FaqCategory};
use Livewire\{Component, WithPagination};

class PublicList extends Component
{
    use WithPagination;

    public $faq_category;

    public function mount(?FaqCategory $faq_category)
    {
        if (!(site_config('faqs') ?? null)) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $this->faq_category = $faq_category;
    }

    public function render()
    {
        $faqs = Faq::query()->active();
        if ($this->faq_category->id) {
            $faqs->whereRelation('FaqCategories', 'id', $this->faq_category->id);
        }
        return view('livewire.faq.list', ['faqs' => $faqs->orderByDesc('id')->paginate()])->layout('layouts.public', ['title' => __('Frequently asked questions')]);
    }
}
